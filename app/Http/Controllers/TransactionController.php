<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

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
        $new_trans->trans_date = $request->transaction["trans_date"];
        $new_trans->payee_id = $request->transaction["payee_id"];
        $new_trans->orig_detail = $request->transaction["orig_detail"];
        $new_trans->new_detail = $request->transaction["new_detail"];
        $new_trans->orig_amt = $request->transaction["orig_amt"];
        $new_trans->new_amt = $request->transaction["new_amt"];
        $new_trans->verified = $request->transaction["verified"];
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
