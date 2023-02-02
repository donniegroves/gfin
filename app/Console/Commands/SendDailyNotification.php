<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
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
            self::sendUserDailyNotification($user_id);
        }
    }

    public function sendUserDailyNotification($user_id = null) {
        if (empty($user_id)){
            $user_id = Auth::user()->id;
            if (empty($user_id)) {
                throw new \Exception("Error Processing Request", 1);
            }
        }

        $tz = 'America/New_York';

        $i=0;
        $date_ar = [];
        while ($i < env('NUM_DAYS_OF_DAILY_NOTIF')) {
            $i++;
            $date_ar[] = (new \DateTime('now -'.$i.' days', new \DateTimeZone($tz)))->format('Y-m-d');
        }
        $date_ar = array_reverse($date_ar);

        $image = $this->generateCalendarImage($user_id, $date_ar);
        $mediaUrl = url('storage/'.$image);

        $twilio_sid = env('TWILIO_SID');
        $twilio_token = env('TWILIO_TOKEN');
        $twilio_from_num = env('TWILIO_FROM_NUM');
        $client = new Client($twilio_sid, $twilio_token);

        $prim_to_num = Settings::getSetting('primary_sms',$user_id);
        $second_to_num = Settings::getSetting('secondary_sms',$user_id);

        if (!empty($prim_to_num)) {
            $client->messages->create($prim_to_num, ["from"=>$twilio_from_num,"body"=>'','mediaUrl' => $mediaUrl]);
        }
        if (!empty($second_to_num)) {
            $client->messages->create($second_to_num, ["from"=>$twilio_from_num,"body"=>'','mediaUrl' => $mediaUrl]);
        }
    }
}
