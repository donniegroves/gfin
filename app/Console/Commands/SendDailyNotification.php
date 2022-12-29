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
            $skip_deps = !(bool) Settings::getSetting('include_deps_in_notifs', $user_id);

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

            $prim_to_num = Settings::getSetting('primary_sms',$user_id);
            $second_to_num = Settings::getSetting('secondary_sms',$user_id);

            if (!empty($prim_to_num)) {
                $client->messages->create($prim_to_num, ["from"=>$twilio_from_num,"body"=>$msg]);
            }
            if (!empty($second_to_num)) {
                $client->messages->create($second_to_num, ["from"=>$twilio_from_num,"body"=>$msg]);
            }
        }
    }
}
