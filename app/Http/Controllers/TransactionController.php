<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Transaction::orderBy('trans_date', 'ASC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_trans = new Transaction;
        // required input
        $new_trans->trans_date = $request->transaction["trans_date"];
        $new_trans->orig_detail = $request->transaction["orig_detail"];
        $new_trans->orig_amt = $request->transaction["orig_amt"];

        // optional input
        $new_trans->payee_id = $request->transaction["payee_id"] ?? null;
        $new_trans->category_id = $request->transaction["category_id"] ?? null;
        $new_trans->approved = $request->transaction["approved"] ?? 0;
        
        $new_trans->save();

        return $new_trans;
    }

    /**
     * Import a user-provided file into transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',
        ]); 

        return $validated;

    //   if ($file = $request->file('file')) {
    //       $path = $file->store('public/files');
    //       $name = $file->getClientOriginalName();

    //       //store your file into directory and db
    //       $save = new File();
    //       $save->name = $file;
    //       $save->store_path= $path;
    //       $save->save();
            
    //       return response()->json([
    //           "success" => true,
    //           "message" => "File successfully uploaded",
    //           "file" => $file
    //       ]);

    //   }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Gets and returns an array of category totals, ordered desc by their total amount, within the selected date ranges
     *
     * @param string $start_date
     * @param string $end_date
     * @return void
     */
    private function getCategoryTotals(string $start_date, string $end_date){
        $query = "
            SELECT
                categories.`name`, 
                SUM(COALESCE(transactions.new_amt, transactions.orig_amt)) AS total
            FROM
                transactions
                LEFT JOIN
                categories
                ON 
                    transactions.category_id = categories.id
            WHERE
                transactions.trans_date >= '" . addslashes($start_date) . "'
                AND transactions.trans_date <= '" . addslashes($end_date) . "'
                AND transactions.approved = 1
            GROUP BY
                categories.`name`
            ORDER BY `total` DESC";
        
        $cat_totals = DB::select($query);
        $result_arr = [];
        foreach ($cat_totals as $cat_row){
            $cat_row->name = $cat_row->name ?? 'Uncategorized';
            $result_arr[$cat_row->name] = $cat_row->total;
        }
        
        return $result_arr;
    }

    public function get_stats(){
        // getting dates
        $week_start = date('Y-m-d',strtotime('last sunday'));
        $week_end = date('Y-m-d', strtotime('this saturday'));
        $month_start = date('Y-m-d', strtotime(date('Y-m-1')));
        $month_end = date('Y-m-t', time());
        $quarter_start = date('Y-m-d', strtotime(date('Y') . '-' . ((ceil(date('n') / 3) * 3) - 2) . '-1'));
        $quarter_end = date('Y-m-t', strtotime(date('Y') . '-' . ((ceil(date('n') / 3) * 3)) . '-1'));
        $year_start = date('Y-m-d', strtotime('first day of january this year'));
        $year_end = date('Y-m-d', strtotime('last day of december this year'));

        return [
            "week" => self::getCategoryTotals($week_start, $week_end),
            "month" => self::getCategoryTotals($month_start, $month_end),
            "quarter" => self::getCategoryTotals($quarter_start, $quarter_end),
            "year" => self::getCategoryTotals($year_start, $year_end)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existing_trans = Transaction::find($id);

        if ($existing_trans){
            $existing_trans->trans_date = !empty($request->transaction['trans_date']) ? $request->transaction['trans_date'] : $existing_trans->trans_date;
            $existing_trans->payee_id = !empty($request->transaction['payee_id']) ? $request->transaction['payee_id'] : $existing_trans->payee_id;
            $existing_trans->category_id = !empty($request->transaction['category_id']) ? $request->transaction['category_id'] : $existing_trans->category_id;
            $existing_trans->new_detail = !empty($request->transaction['new_detail']) ? $request->transaction['new_detail'] : $existing_trans->new_detail;
            $existing_trans->new_amt = !empty($request->transaction['new_amt']) ? $request->transaction['new_amt'] : $existing_trans->new_amt;
            $existing_trans->approved = isset($request->transaction['approved']) ? (bool) $request->transaction['approved'] : $existing_trans->approved;
            $existing_trans->save();

            return $existing_trans;
        }

        return 'Transaction not found.';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existing_trans = Transaction::find($id);

        if ($existing_trans){
            $existing_trans->delete();
            return 'Transaction deleted.';
        }

        return 'Transaction not found.';
    }
}
