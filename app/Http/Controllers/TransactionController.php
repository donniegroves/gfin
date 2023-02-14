<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!empty($request->search)) {
            return (new Transaction())
                ->where('user_id', Auth::user()->id)
                ->where(function($query) use ($request) {
                    $query
                        ->where('orig_detail','LIKE', '%'.$request->search.'%')
                        ->orWhere('new_detail','LIKE', '%'.$request->search.'%')
                        ->orWhere('orig_amt','LIKE', '%'.$request->search.'%')
                        ->orWhere('new_amt','LIKE', '%'.$request->search.'%')
                        ->orWhere('trans_date','LIKE', '%'.$request->search.'%');
                })
                ->orderBy('trans_date', 'DESC')
                ->paginate(50);
        }
        else if ($request->range === "all"){
            return Transaction::where('user_id', Auth::user()->id)
                ->orderBy('trans_date', 'DESC')
                ->get();
        }
        else {
            return Transaction::where('user_id', Auth::user()->id)
                ->orderBy('trans_date', 'DESC')
                ->paginate(50);
        }
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
        $new_trans->user_id = Auth::user()->id;

        // required input
        $new_trans->trans_date = $request->transaction["trans_date"];
        $new_trans->orig_detail = $request->transaction["orig_detail"];
        $new_trans->orig_amt = $request->transaction["orig_amt"];

        // optional input
        $new_trans->payee_id = $request->transaction["payee_id"] ?? null;
        $new_trans->category_id = $request->transaction["category_id"] ?? null;
        $new_trans->approved = $request->transaction["approved"] ?? 0;

        if (!empty($request->transaction["payee_id"]) && is_string($request->transaction["payee_id"])) {
            $new_trans->payee_id = (new Payee())->getOrCreateFromString($request->transaction["payee_id"]);
        }

        if (!empty($request->transaction["category_id"]) && is_string($request->transaction["category_id"])) {
            $new_trans->category_id = (new Category())->getOrCreateFromString($request->transaction["category_id"]);
        }

        $new_trans->save();

        return $new_trans;
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

        $trans_obj = new Transaction();
        $result = $trans_obj->importTransactionsByCSV($validated['file']);

        return $result;
    }

    public function getDailyTotal(string $date, int $user_id, bool $skip_deps): int {
        $query = "SELECT SUM(COALESCE ( transactions.new_amt, transactions.orig_amt )) AS total
        FROM transactions
        WHERE transactions.trans_date = '" . addslashes($date) . "'
        AND transactions.approved = 1
        AND transactions.user_id = ".addslashes($user_id)
        .($skip_deps ? " AND (transactions.orig_amt <= 0 OR transactions.new_amt <= 0)" : "")."
        ORDER BY `total` DESC";

        $total = DB::select($query);
        $total = (int) $total[0]->total;
        return $total;
    }

    /**
     * Gets and returns an array of category totals, ordered desc by their total amount, within the selected date ranges
     *
     * @param string $start_date
     * @param string $end_date
     * @param integer $user_id
     * @return void
     */
    public function getCategoryTotals(string $start_date, string $end_date, int $user_id, bool $skip_deps = false){
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
                AND transactions.user_id = ".addslashes($user_id)
                .($skip_deps ? " AND (transactions.orig_amt <= 0 OR transactions.new_amt <= 0)" : "")."
            GROUP BY
                categories.`name`
            ORDER BY `total`";

        $cat_totals = DB::select($query);
        $result_arr = [];
        foreach ($cat_totals as $cat_row){
            $cat_row->name = $cat_row->name ?? 'Uncategorized';
            $result_arr[$cat_row->name] = $cat_row->total;
        }

        return [
            "start_date" => $start_date,
            "end_date" => $end_date,
            "cat_totals" => $result_arr
        ];
    }

    public function get_stats():array {
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
            "yesterday" => self::getCategoryTotals(date('Y-m-d',strtotime('yesterday')), date('Y-m-d',strtotime('yesterday')), Auth::user()->id),
            "today" => self::getCategoryTotals(date('Y-m-d', time()), date('Y-m-d', time()), Auth::user()->id),
            "week" => self::getCategoryTotals($week_start, $week_end, Auth::user()->id),
            "month" => self::getCategoryTotals($month_start, $month_end, Auth::user()->id),
            "quarter" => self::getCategoryTotals($quarter_start, $quarter_end, Auth::user()->id),
            "year" => self::getCategoryTotals($year_start, $year_end, Auth::user()->id)
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
        if ($existing_trans->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_trans){
            if (!empty($request->transaction["payee_id"]) && is_string($request->transaction["payee_id"])) {
                $existing_trans->payee_id = (new Payee())->getOrCreateFromString($request->transaction["payee_id"]);
            }
            else {
                $existing_trans->payee_id = $request->transaction['payee_id'] ?? $existing_trans->payee_id;
            }

            if (!empty($request->transaction["category_id"]) && is_string($request->transaction["category_id"])) {
                $existing_trans->category_id = (new Category())->getOrCreateFromString($request->transaction["category_id"]);
            }
            else {
                $existing_trans->category_id = $request->transaction['category_id'] ?? $existing_trans->category_id;
            }


            $existing_trans->user_id =      $request->transaction['user_id']       ?? $existing_trans->user_id;
            $existing_trans->trans_date =   $request->transaction['trans_date']    ?? $existing_trans->trans_date;
            $existing_trans->new_detail =   $request->transaction['new_detail']    ?? $existing_trans->new_detail;
            $existing_trans->new_amt =      $request->transaction['new_amt']       ?? $existing_trans->new_amt;
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
        if ($existing_trans->user_id !== Auth::user()->id){
            return 'Permissions problem.';
        }

        if ($existing_trans){
            $existing_trans->delete();
            return 'Transaction deleted.';
        }

        return 'Transaction not found.';
    }
}
