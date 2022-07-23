<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\Entities\User;

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

        // STORE THIS ACCESS TOKEN IN DB FOR USER.

        return $access_token;
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function is_account_connected()
    {
        return '{"status":false}';
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function get_trans()
    {
        $plaid = new Plaid(self::PLAID_CLIENT_ID, self::PLAID_SECRET_KEY, "sandbox");

        $access_token = 'xxx'; // LOOK UP ACCESS_TOKEN IN DATABASE.
        $start_date = new \DateTime('1 month ago');
        $end_date = new \DateTime('yesterday');

        $transactions = $plaid->transactions->list($access_token, $start_date, $end_date);
        
        return $transactions;
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
