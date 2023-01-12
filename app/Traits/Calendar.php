<?php

namespace App\Traits;

use App\Models\Settings;
use App\Http\Controllers\TransactionController;
use HeadlessChromium\BrowserFactory;

trait Calendar
{
    public function generateCalendarImage(int $user_id, $date_ar)
    {
        $skip_deps = !(bool) Settings::getSetting('include_deps_in_calcs', $user_id);

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
        }

        .square {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 2px solid red;
            margin-right: 2px;
            font-size: 1.5em;
        }

        .square .label {
            position: absolute;
            top: 0;
            right: 0;
            margin: 2px;
            font-size: 0.6em;
        }
    </style>
</head>

<body>
    <div class="container">
    ';

        foreach ($stats as $date => $total) {
            $total = $total*-1;
            $disp_date = new \DateTime($date);

            $html .= '
                <div class="square">
                    <div class="label">'.$disp_date->format("jS").'</div>
                    $'.$total.'
                </div>';
        }
        $html .= '</div></body></html>';

        $this->saveScreenshotFromHtml($html, "storage/app/public/notifs/{$user_id}.png");

        return "notifs/{$user_id}.png";
    }

    private function saveScreenshotFromHTML($html, $file_path) {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser([
            'windowSize'   => [300, 70],
            "noSandbox" => true
        ]);

        try {
            $page = $browser->createPage();
            $page->setHtml($html);
            $page->screenshot()->saveToFile($file_path);
        } finally {
            $browser->close();
        }

        return $file_path;
    }
}

?>

