<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TransactionController;
use App\Models\Settings;
use Twilio\Rest\Client;

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
        $user_ids = [];
        $stg_rows = Settings::where('stg_name', 'send_daily_sms')->get()->toArray();
        foreach ($stg_rows as $row){
            if ((bool) $row['stg_val']) {
                $user_ids[] = $row['user_id'];
            }
        }

        foreach ($user_ids as $user_id){
            $skip_deps = Settings::where('stg_name', 'include_deps_in_notifs')
            ->where('user_id', $user_id)
            ->get()->first()->toArray();
            $skip_deps = !(bool) $skip_deps['stg_val'];

            $timezone = 'America/New_York';
            $yesterday = new \DateTime('yesterday', new \DateTimeZone($timezone));
            $yesterday = $yesterday->format('Y-m-d');

            $tcont = new TransactionController();
            $stats = [];
            $stats['yesterday'] = $tcont->getCategoryTotals(
                $yesterday, 
                $yesterday, 
                $user_id,
                $skip_deps
            );

            $msg = "Yesterday, you spent the following on these categories:\r\n";
            foreach ($stats['yesterday'] as $cat_name => $amt){
                $msg .= $cat_name . ": " . $amt*-1 ."\r\n";
            }

            $twilio_sid = env('TWILIO_SID');
            $twilio_token = env('TWILIO_TOKEN');
            $twilio_from_num = env('TWILIO_FROM_NUM');
            $client = new Client($twilio_sid, $twilio_token);

            $to_num = Settings::where('stg_name', 'primary_sms')
            ->where('user_id', $user_id)
            ->get()->first()->toArray();
            $to_num = $to_num['stg_val'];

            $client->messages->create(
                $to_num,
                [
                    'from' => $twilio_from_num,
                    'body' => $msg
                ]
            );
        }
    }
}
