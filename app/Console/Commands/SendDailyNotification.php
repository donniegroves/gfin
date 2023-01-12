<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings;
use Twilio\Rest\Client;

class SendDailyNotification extends Command
{
    use \App\Traits\Calendar;

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
        // getting user_ids that are eligible for daily notifications
        $user_ids = [];
        $stg_rows = Settings::where('stg_name', 'send_daily_sms')->get()->toArray();
        foreach ($stg_rows as $row){
            if ((bool) $row['stg_val']) {
                $user_ids[] = $row['user_id'];
            }
        }

        foreach ($user_ids as $user_id){
            $tz = 'America/New_York';
            $date_ar = [
                (new \DateTime('now -5 days', new \DateTimeZone($tz)))->format('Y-m-d'),
                (new \DateTime('now -4 days', new \DateTimeZone($tz)))->format('Y-m-d'),
                (new \DateTime('now -3 days', new \DateTimeZone($tz)))->format('Y-m-d'),
                (new \DateTime('now -2 days', new \DateTimeZone($tz)))->format('Y-m-d'),
                (new \DateTime('now -1 days', new \DateTimeZone($tz)))->format('Y-m-d')
            ];

            $image = $this->generateCalendarImage($user_id, $date_ar);
            $mediaUrl = url('storage/'.$image);

            $twilio_sid = env('TWILIO_SID');
            $twilio_token = env('TWILIO_TOKEN');
            $twilio_from_num = env('TWILIO_FROM_NUM');
            $client = new Client($twilio_sid, $twilio_token);

            $prim_to_num = Settings::getSetting('primary_sms',$user_id);
            $second_to_num = Settings::getSetting('secondary_sms',$user_id);

            $msg = "GFin:\r\n";

            if (!empty($prim_to_num)) {
                $client->messages->create($prim_to_num, ["from"=>$twilio_from_num,"body"=>$msg,'mediaUrl' => $mediaUrl]);
            }
            if (!empty($second_to_num)) {
                $client->messages->create($second_to_num, ["from"=>$twilio_from_num,"body"=>$msg,'mediaUrl' => $mediaUrl]);
            }
        }
    }
}
