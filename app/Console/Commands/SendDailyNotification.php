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
        $recipient_arr = [];
        $stg_rows = Settings::where('stg_name', 'enable_sms_notifs')->get()->toArray();
        foreach ($stg_rows as $row){
            $recipient_arr[] = $row['user_id'];
        }

        foreach ($recipient_arr as $recipient){
            $timezone = 'America/New_York';
            $yesterday = new \DateTime('yesterday', new \DateTimeZone($timezone));
            $yesterday = $yesterday->format('Y-m-d');

            $tcont = new TransactionController();
            $stats = [];
            $stats['yesterday'] = $tcont->getCategoryTotals(
                $yesterday, 
                $yesterday, 
                $recipient
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
            ->where('user_id', $recipient)
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
