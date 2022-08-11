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
        $new_trans->verified = $request->transaction["verified"] ?? 0;
        
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
     * Gets and returns an array of category totals, ordered desc by their total amount.
     * Takes Quantity and Interval as arguments. Passing 2 and 'month' will account for all transactions 
     * within the past 2 months.
     *
     * @param integer $quant
     * @param string $interval
     * @return void
     */
    private function getCategoryTotals(int $quant, string $interval){
        $interval = strtoupper($interval);
        if (!in_array($interval,['DAY','YEAR','MONTH','YEAR'])){
            return false;
        }

        $query = '
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
                transactions.trans_date >= DATE_SUB(CURDATE(), INTERVAL ' . $quant . ' ' . $interval .')
            GROUP BY
                categories.`name`
            ORDER BY `total` DESC';
        
        $cat_totals = DB::select($query);
        $result_arr = [];
        foreach ($cat_totals as $cat_row){
            $cat_row->name = $cat_row->name ?? 'Uncategorized';
            $result_arr[$cat_row->name] = $cat_row->total;
        }
        
        return $result_arr;
    }

    public function get_stats(){
        return self::getCategoryTotals(2, 'year');
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
            $existing_trans->verified = isset($request->transaction['verified']) ? (bool) $request->transaction['verified'] : $existing_trans->verified;
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
