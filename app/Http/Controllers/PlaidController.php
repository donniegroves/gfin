<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\Entities\User;
use App\Models\Account;
use App\Models\PlaidTokens;
use App\Models\PayeePattern;
use App\Models\CategoryPattern;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PlaidController extends Controller
{
    private $pclient;
    private $payee_patterns;
    private $cat_patterns;
    private $trans;
    private $accts;

    public function __construct()
    {
        $this->pclient = new Plaid(env('PLAID_CLIENT_ID'), env('PLAID_SECRET_KEY'), "sandbox");
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function create_link_token()
    {
        $token_user = new User(Auth::user()->id);
        
        return $this->pclient->tokens->create(env('APP_NAME'),'en',['US','CA'], $token_user, ['transactions']);
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function exchange_public_token(Request $request)
    {
        $access_token = $this->pclient->items->exchangeToken($request->input('public_token'));

        $plaid_token = new PlaidTokens();
        $plaid_token->token = $access_token->access_token;
        $plaid_token->user_id = Auth::user()->id;
        $saved = $plaid_token->save();

        if (!$saved){
            return response('Problem with saving token.', 500);
        }

        return response('Token saved successfully.', 200);
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function import_transactions()
    {
        $user = \App\Models\User::where('id', Auth::user()->id)->get()->first();
        $start_date = new \DateTime('1 month ago');
        $end_date = new \DateTime('today');
        
        return Transaction::importUserTransactionsFromPlaid($user, $start_date, $end_date);
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function is_account_connected()
    {
        $user_id = Auth::user()->id;
        $token_res = PlaidTokens::where('user_id', $user_id)->get();

        if (count($token_res) !== 1){
            return response('0', 200);
        }

        return response(1, 200);
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function unlink_account()
    {
        $user_id = Auth::user()->id;
        $deleted = PlaidTokens::where('user_id', $user_id)->delete();

        if (!$deleted){
            return response('Problem unlinking account', 500);
        }

        return response('Account unlinked successfully.', 200);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
