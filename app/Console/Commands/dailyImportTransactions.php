<?php

namespace App\Console\Commands;

use App\Http\Controllers\PlaidController;
use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class dailyImportTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:dailyImportTransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports transactions for all users daily.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users_with_plaid = Account::select('user_id')->groupBy('user_id')->pluck('user_id')->toArray();

        foreach ($users_with_plaid as $u) {
            // set logged in user
            $user_instance = User::where('id', $u)->get()->first();
            Auth::login($user_instance);

            // retrieve transactions for the user from plaid
            $start_date = new \DateTime('1 week ago');
            $end_date = new \DateTime('today');
            $transaction = new Transaction();
            $transaction->importUserTransactionsFromPlaid($start_date, $end_date);
        }
    }
}
