<?php

namespace App\Traits;

use App\Models\Settings;
use App\Http\Controllers\TransactionController;
use HeadlessChromium\BrowserFactory;
use Aws\S3\S3Client;

trait Calendar
{
    public function generateCalendarImage(int $user_id, $date_ar)
    {
        $skip_deps = !(bool) Settings::getSetting('include_deps_in_calcs', $user_id);
        $daily_budget = Settings::getSetting('daily_exp_budget', $user_id);

        $tcont = new TransactionController();
        $stats = [];
        foreach ($date_ar as $day) {
            $stats[$day] = $tcont->getDailyTotal($day,$user_id,$skip_deps);
        }

        $html = '
<!DOCTYPE html>
<html>

<head>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 0px;
            margin-bottom: 100px;
        }

        .square {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 2px solid grey;
            margin-right: 2px;
            font-size: 2.2em;
        }

        .square .label {
            position: absolute;
            top: 0;
            right: 0;
            margin: 2px;
            font-size: 0.6em;
        }

        .square.under-budget {
            border: 2px solid green;
        }
        .square.over-budget {
            border: 2px solid red;
        }

        .breaker {
            flex-basis: 100%;
            margin: 1px;
        }
    </style>
</head>

<body>
    <div class="container">
    ';

        $first_date = true;
        foreach ($stats as $date => $total) {
            $total = $total*-1;
            $disp_date = new \DateTime($date);

            if ($first_date) {
                $blanks_needed = $disp_date->format('w');
                for ($i=0; $i<$blanks_needed; $i++) {
                    $html .= '<div class="square"></div>';
                }
            }

            $html .= '
                <div class="square'.($daily_budget >= $total ? ' under-budget' : ' over-budget').'">
                    <div class="label">'.$disp_date->format("jS").'</div>
                    $'.$total.'
                </div>';

            $is_saturday = $disp_date->format('w') == 6;
            $html .= $is_saturday ? '<div class="breaker"></div>' : '';

            $first_date = false;
        }
        $html .= '</div></body></html>';

        $url = $this->saveScreenshotFromHtml($html, __DIR__."/../../storage/app/public/notifs/{$user_id}.png");

        return $url;
    }

    private function saveScreenshotFromHTML($html, $file_path) {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser([
            'windowSize'   => [630,530],
            "noSandbox" => true
        ]);

        try {
            if (!is_writable($file_path)) {
                throw new Exception($file_path . " is not writable.");
            }
            $page = $browser->createPage();
            $page->setHtml($html);
            $page->screenshot()->saveToFile($file_path);
            $browser->close();
        } catch (Exception $e) {
            echo "An exception has occurred: " . $e->getMessage();
        }

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);

        $key = basename($file_path);
        $result = $s3->putObject([
            'Bucket' => config('services.aws.bucket'),
            'Key' => $key,
            'SourceFile' => $file_path,
            'ACL' => 'public-read',
        ]);
        $url = $s3->getObjectUrl(config('services.aws.bucket'), $key);

        return $url;
    }
}

?>

