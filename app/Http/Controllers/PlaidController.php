<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\Entities\User;
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
        $user_id = Auth::user()->id;
        $token_res = PlaidTokens::where('user_id', $user_id)->get();
        if (count($token_res) !== 1){
            return response('Problem determining which token to use.', 500);
        }
        $access_token = $token_res->first()->token;

        $start_date = new \DateTime('1 month ago');
        $end_date = new \DateTime('yesterday');

        $transactions = $this->pclient->transactions->list($access_token, $start_date, $end_date);
        self::prepareInitData();

        // separate existing from new and discard $existing_trans
        self::separateExistingTransFromNew($transactions->transactions, $existing_trans, $new_trans);

        // separate matched from unmatched
        self::separateMatchedTransFromUnmatched($new_trans, $matched_trans, $unmatched_trans);

        // import all new transactions with matched data
        self::importTransactions(array_merge($matched_trans,$unmatched_trans));

        return [
            "total_incoming" => count($transactions->transactions),
            "existing_skipped" => count($existing_trans),
            "new_processed" => count($new_trans),
            "matched_trans" => count($matched_trans),
            "unmatched_trans" => count($unmatched_trans)
        ];
    }

    /**
     * Determines if transactions already exist and separates them into two separate arrays
     *
     * @param array $input_trans
     * @param array $existing_trans
     * @param array $new_trans
     * @return void
     */
    private function separateExistingTransFromNew(array $input_trans, ?array &$existing_trans, ?array &$new_trans){
        $existing_trans = $new_trans = [];
        foreach ($input_trans as $one_tran){
            if (self::transactionExists($one_tran)){
                $existing_trans[] = $one_tran;
            }
            else{
                $new_trans[] = $one_tran;
            }
        }
    }

    /**
     * Separates matched and unmatched transactions into two arrays.
     * A transaction is considered to be matched if it matches either payee or category based on patterns.
     *
     * @param array $input_trans
     * @param array $matched_trans
     * @param array $unmatched_trans
     * @return void
     */
    private function separateMatchedTransFromUnmatched(array $input_trans, ?array &$matched_trans, ?array &$unmatched_trans){
        $matched_trans = $unmatched_trans = [];
        foreach ($input_trans as $one_tran){
            $is_match = false;

            $result_tran = new Transaction([
                "trans_date" => $one_tran->date,
                "payee_id" => null,
                "category_id" => null,
                "orig_detail" => $one_tran->name,
                "orig_amt" => $one_tran->amount,
                "verified" => 0,
            ]);

            // determine if payee is matchable
            foreach($this->payee_patterns as $ppat){
                if (str_contains($one_tran->name, $ppat['pattern'])){
                    $result_tran->payee_id = $ppat['payee_id'];
                    $is_match = true;
                    break;
                }
            }
            
            // determine if category is matchable
            foreach($this->cat_patterns as $cpat){
                if (str_contains($one_tran->name, $cpat['pattern'])){
                    $result_tran->category_id = $cpat['category_id'];
                    $is_match = true;
                    break;
                }
            }

            if ($is_match){
                $matched_trans[] = $result_tran;
            }
            else {
                $unmatched_trans[] = $result_tran;
            }
        }
    }

    /**
     * Adds transactions into database.
     *
     * @param array $trans_arr
     * @return void
     */
    private function importTransactions(array $trans_arr){
        foreach ($trans_arr as $tran){
            $transaction = new Transaction([
                "trans_date" => $tran->trans_date,
                "payee_id" => $tran->payee_id,
                "category_id" => $tran->category_id,
                "orig_detail" => $tran->orig_detail,
                "orig_amt" => $tran->orig_amt,
                "verified" => 1,
            ]);
            $transaction->save();
        }
    }

    /**
     * Concatenates current transactions' date, detail, and amount and compares to incoming $tran.
     * Returns true/false
     *
     * @param object $tran
     * @return boolean
     */
    private function transactionExists(object $tran):bool{
        $in_tran_str = 'gf'.$tran->date.$tran->name.number_format($tran->amount,2,'.','');
        return in_array($in_tran_str,$this->trans);
    }

    /**
     * Sets initial data, including concatenated list of transactions
     *
     * @return void
     */
    private function prepareInitData(){
        $this->payee_patterns = PayeePattern::get()->toArray();
        $this->cat_patterns = CategoryPattern::get()->toArray();
        $this->trans = Transaction::get()->toArray();
        $temp_arr = [];
        foreach ($this->trans as $tran){
            $temp_arr[] = 'gf' . $tran['trans_date'] . $tran['orig_detail'] . $tran['orig_amt'];
        }
        $this->trans = $temp_arr;
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
