<?php

namespace App\Models;

use App\Http\Controllers\SettingsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'stg_name', 'stg_val'];

    public static function getSetting(string $stg_name, int $user_id = null) {
        if (empty($stg_name) ||
            !in_array($stg_name, SettingsController::PERMITTED_SETTINGS_ARR) ||
            (is_null($user_id) && Auth::check())
        ) {
            return null;
        }
        
        $res = self::where('stg_name', $stg_name)->where('user_id', $user_id)->first();

        if (is_null($res)) {
            return null;
        }

        return $res->toArray()['stg_val'];
    }
}
