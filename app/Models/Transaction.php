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

    public static function importUserTransactionsFromPlaid(User $user, \DateTime $start_date, \DateTime $end_date) {
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
        $incoming_accts = $transactions->accounts;

        // importing accounts into db
        $acct_obj = new Account();
        $acct_obj->import_accounts($incoming_accts);

        // prepping vars for matching process
        $cur_trans = self::where('user_id', $user_id)->get()->toArray();

        // generating fingerprints for each transaction based on date, detail, and amount.
        $tr_fingerprints = [];
        foreach ($cur_trans as $tran){
            $tr_fingerprints[] = 'gf' . $tran['trans_date'] . $tran['orig_detail'] . number_format(($tran['orig_amt']*-1),2,'.','');
        }

        self::separateExistingTransFromNew($transactions->transactions, $tr_fingerprints, $existing_trans, $new_trans);
        self::separateMatchedTransFromUnmatched($new_trans, $matched_trans, $unmatched_trans);
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
    private static function separateExistingTransFromNew(array $input_trans, $fingerprints, ?array &$existing_trans, ?array &$new_trans){
        $existing_trans = $new_trans = [];
        foreach ($input_trans as $one_tran){
            if (self::transactionExists($one_tran, $fingerprints)){
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
    private static function separateMatchedTransFromUnmatched(array $input_trans, ?array &$matched_trans, ?array &$unmatched_trans){
        $matched_trans = $unmatched_trans = [];
        foreach ($input_trans as $one_tran){
            $is_match = false;

            $result_tran = new Transaction([
				"user_id"		        => Auth::user()->id,
                "trans_date" 	        => $one_tran->date,
                "payee_id" 		        => null,
                "category_id" 	        => null,
                "account_id"            => $one_tran->account_id,
                "plaid_transaction_id"  => $one_tran->transaction_id,
                "orig_detail" 	        => $one_tran->name,
                "orig_amt" 		        => $one_tran->amount,
                "approved" 		        => 0,
            ]);

            // determine if payee is matchable
            $payee_patterns = PayeePattern::where('user_id', Auth::user()->id)->get()->toArray();
            foreach($payee_patterns as $ppat){
                if (str_contains($one_tran->name, $ppat['pattern'])){
                    $result_tran->payee_id = $ppat['payee_id'];
                    $is_match = true;
                    break;
                }
            }
            
            // determine if category is matchable
            $cat_patterns = CategoryPattern::where('user_id', Auth::user()->id)->get()->toArray();
            foreach($cat_patterns as $cpat){
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
     * Concatenates current transactions' date, detail, and amount and compares to incoming $tran.
     * Returns true/false
     *
     * @param object $tran
     * @return boolean
     */
    private static function transactionExists(object $tran, Array $fingerprints):bool{
        // TODO: should be comparing transaction id from plaid instead.
        $in_tran_str = 'gf'.$tran->date.$tran->name.number_format($tran->amount,2,'.','');
        return in_array($in_tran_str,$fingerprints);
    }

    /**
     * Adds transactions into database.
     *
     * @param array $trans_arr
     * @return void
     */
    private static function importTransactions(array $trans_arr){
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
