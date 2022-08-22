<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TransactionController;
use App\Models\Settings;

class SendDailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sendDailyNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily budget update notification to users that have it enabled.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recipient_arr = [];
        $stg_rows = Settings::where('stg_name', 'enable_sms_notifs')->get()->toArray();
        foreach ($stg_rows as $row){
            $recipient_arr[] = $row['user_id'];
        }

        foreach ($recipient_arr as $recipient){
            $tcont = new TransactionController();
            $stats = [];
            $stats['yesterday'] = $tcont->getCategoryTotals(
                date('Y-m-d',strtotime('yesterday')), 
                date('Y-m-d',strtotime('yesterday')), 
                $recipient
            );
            $stats['week'] = $tcont->getCategoryTotals(
                date('Y-m-d',strtotime('last sunday')), 
                date('Y-m-d', strtotime('this saturday')), 
                $recipient
            );

            $msg  = "This is your daily budget email!\r\n";
            $msg .= "Yesterday, you spent the following on these categories:\r\n";
            foreach ($stats['yesterday'] as $cat_name => $amt){
                $msg .= $cat_name . ": " . $amt ."\r\n";
            }
            $msg .= "\r\n";
            $msg .= "So far, this week you've spent the following on these categories:\r\n";
            foreach ($stats['week'] as $cat_name => $amt){
                $msg .= $cat_name . ": " . $amt ."\r\n";
            }

            // TODO: Twilio handling here.
        }
    }
}
