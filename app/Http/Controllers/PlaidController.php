<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\Entities\User;
use App\Models\PlaidTokens;
use Illuminate\Support\Facades\Auth;

class PlaidController extends Controller
{


    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function create_link_token()
    {
        $plaid = new Plaid(self::PLAID_CLIENT_ID, self::PLAID_SECRET_KEY, "sandbox");

        $token_user = new User(self::ARBITRARY_USER_ID);
        
        $token = $plaid->tokens->create(self::APP_NAME,'en',['US','CA'], $token_user, ['transactions']);
        
        return $token;
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function exchange_public_token(Request $request)
    {
        $plaid = new Plaid(self::PLAID_CLIENT_ID, self::PLAID_SECRET_KEY, "sandbox");

        $access_token = $plaid->items->exchangeToken($request->input('public_token'));

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
        $user_id = Auth::user()->id;
        $token_res = PlaidTokens::where('user_id', $user_id)->get();
        
        if (count($token_res) !== 1){
            return response('Problem determining which token to use.', 500);
        }

        $access_token = $token_res->first()->token;
        $plaid = new Plaid(self::PLAID_CLIENT_ID, self::PLAID_SECRET_KEY, "sandbox");

        $start_date = new \DateTime('1 month ago');
        $end_date = new \DateTime('yesterday');

        $transactions = $plaid->transactions->list($access_token, $start_date, $end_date);
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
