<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use TomorrowIdeas\Plaid\Plaid;
use App\Models\PayeePattern;
use App\Models\CategoryPattern;

class Transaction extends Model
{
    public $cur_trans;
    public $tr_fingerprints;
    public $plaid_trans_ids;

    protected $fillable = [
        "user_id",
        "trans_date",
        "payee_id",
        "category_id",
        "account_id",
        "plaid_transaction_id",
        "orig_detail",
        "orig_amt",
        "approved",
    ];

    use HasFactory;

    public function importTransactionsByCSV($file) {
        $trans_from_csv =[];
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $trans_from_csv[] = [
                    "date" => date('Y-m-d', strtotime($data[0])),
                    "amount" => $data[1],
                    "description" => $data[4]
                ];
            }
            fclose($handle);
        }

        self::separateExistingTransFromNew($trans_from_csv, $existing_trans, $new_trans);
        self::separateMatchedTransFromUnmatched($new_trans, $matched_trans, $unmatched_trans);

        $trans_arr = array_merge($matched_trans,$unmatched_trans);
        foreach ($trans_arr as $tran){
            $transaction = new Transaction([
				"user_id"		=> $tran->user_id,
                "trans_date" 	=> $tran->trans_date,
                "payee_id" 		=> $tran->payee_id,
                "category_id" 	=> $tran->category_id,
                "orig_detail" 	=> preg_replace('/\s+/',' ',$tran->orig_detail),
                "orig_amt" 		=> $tran->orig_amt,
                "approved" 		=> ($tran->payee_id && $tran->category_id) ? 1 : 0,
            ]);
            $transaction->save();
        }

        return [
            "total_incoming" => count($trans_from_csv),
            "existing_skipped" => count($existing_trans),
            "new_processed" => count($new_trans),
            "matched_trans" => count($matched_trans),
            "unmatched_trans" => count($unmatched_trans)
        ];
    }

    private function convertTransactionsToPlaid(array $trans): array {
        $output_arr = [];
        foreach ($trans as $tran) {
            $output_arr[] = (object) [
                "date"          => $tran['date'],
                "name"          => (float) $tran['amount'],
                "amount"        => $tran['description'],
                "account_id"    => null,
                "transaction_id"=> null
            ];
        }
        return $output_arr;
    }

    private function setCurrentTransAndFingerprints() {
        // prepping vars for matching process
        $this->cur_trans = self::where('user_id', Auth::user()->id)->get()->toArray();
        $this->plaid_trans_ids = $this->tr_fingerprints = [];
        foreach ($this->cur_trans as $tran) {
            $this->plaid_trans_ids[] = $tran['plaid_transaction_id'];
            $this->tr_fingerprints[] = 'gf' . $tran['trans_date'] . $tran['orig_detail'] . number_format(($tran['orig_amt']),2,'.','');
        }
    }

    public function importUserTransactionsFromPlaid(\DateTime $start_date, \DateTime $end_date) {
        // getting user's access token from db.
        $user_id = Auth::user()->id;
        $token_res = PlaidTokens::where('user_id', $user_id)->get();
        if (count($token_res) !== 1){
            return response('Problem determining which token to use.', 500);
        }
        $access_token = $token_res->first()->token;

        // getting transactions from plaid for user.
        $pclient = new Plaid(env('PLAID_CLIENT_ID'), env('PLAID_SECRET_KEY'), env('PLAID_ENVIRONMENT'));
        $transactions = $pclient->transactions->list($access_token, $start_date, $end_date);

        $this->setCurrentTransAndFingerprints();

        self::separateExistingTransFromNew($transactions->transactions, $existing_trans, $new_trans);
        self::separateMatchedTransFromUnmatched($new_trans, $matched_trans, $unmatched_trans);
        self::importTransactionsWithPlaid(array_merge($matched_trans,$unmatched_trans));

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
        $this->setCurrentTransAndFingerprints();

        // determine if input transactions are from plaid or csv
        $input_type = is_array(reset($input_trans)) ? 'csv' : 'plaid';

        $existing_trans = $new_trans = [];

        if ($input_type == 'csv') {
            foreach ($input_trans as $tran) {
                $in_tran_str = 'gf'.$tran['date'].$tran['description'].number_format($tran['amount'],2,'.','');
                if (in_array($in_tran_str, $this->tr_fingerprints)) {
                    $existing_trans[] = $tran;
                }
                else {
                    $new_trans[] = $tran;
                }
            }
        }
        else if ($input_type == 'plaid') {
            foreach ($input_trans as $tran) {
                if (in_array($tran->transaction_id,$this->plaid_trans_ids)) {
                    $existing_trans[] = $tran;
                }
                else {
                    $new_trans[] = $tran;
                }
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
    private static function separateMatchedTransFromUnmatched(array $input_trans, ?array &$matched_trans, ?array &$unmatched_trans){
        // determine if input transactions are from plaid or csv
        $input_type = is_array(reset($input_trans)) ? 'csv' : 'plaid';

        $matched_trans = $unmatched_trans = [];
        foreach ($input_trans as $one_tran){
            $is_match = false;

            $trans_date = $input_type == 'csv' ? $one_tran['date'] : $one_tran->date;
            $account_id = $input_type == 'csv' ? null : $one_tran->account_id;
            $plaid_transaction_id = $input_type == 'csv' ? null : $one_tran->transaction_id;
            $orig_detail = $input_type == 'csv' ? $one_tran['description'] : $one_tran->name;
            $orig_amt = $input_type == 'csv' ? $one_tran['amount'] : $one_tran->amount;

            $result_tran = new Transaction([
				"user_id"		        => Auth::user()->id,
                "trans_date" 	        => $trans_date,
                "payee_id" 		        => null,
                "category_id" 	        => null,
                "account_id"            => $account_id,
                "plaid_transaction_id"  => $plaid_transaction_id,
                "orig_detail" 	        => $orig_detail,
                "orig_amt" 		        => $orig_amt,
                "approved" 		        => 0,
            ]);

            // determine if payee is matchable
            $payee_patterns = PayeePattern::where('user_id', Auth::user()->id)->get()->toArray();
            foreach($payee_patterns as $ppat){
                if (str_contains($orig_detail, $ppat['pattern'])){
                    $result_tran->payee_id = $ppat['payee_id'];
                    $is_match = true;
                    break;
                }
            }
            
            // determine if category is matchable
            $cat_patterns = CategoryPattern::where('user_id', Auth::user()->id)->get()->toArray();
            foreach($cat_patterns as $cpat){
                if (str_contains($orig_detail, $cpat['pattern'])){
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
    private static function importTransactionsWithPlaid(array $trans_arr){
        $accts = Account::where('user_id', '=', Auth::user()->id)->pluck('id', 'plaid_account_id')->toArray();
        foreach ($trans_arr as $tran){
            $transaction = new Transaction([
				"user_id"		=> $tran->user_id,
                "trans_date" 	=> $tran->trans_date,
                "payee_id" 		=> $tran->payee_id,
                "category_id" 	=> $tran->category_id,
                "account_id"    => $accts[$tran->account_id],
                "plaid_transaction_id" => $tran->plaid_transaction_id,
                "orig_detail" 	=> preg_replace('/\s+/',' ',$tran->orig_detail),
                "orig_amt" 		=> $tran->orig_amt * -1,
                "approved" 		=> ($tran->payee_id && $tran->category_id) ? 1 : 0,
            ]);
            $transaction->save();
        }
    }
}
