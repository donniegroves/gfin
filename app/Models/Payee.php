<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PayeeController;
use Illuminate\Http\Request;

class Payee extends Model
{
    protected $fillable = [
        "user_id",
        "name",
    ];

    use HasFactory;

    public function getOrCreateFromString(string $string): int|bool {
        // search for payee
        $all_payees = self::get()->pluck("name","id")->toArray();
        $found_payee_id = array_search(strtolower($string), array_map('strtolower',$all_payees));

        // adding payee if it doesn't exist.
        if (!$found_payee_id) {
            $new_request = new Request();
            $new_request->payee =
                    ['name' => $string];
            $found_payee = (new PayeeController())->store($new_request);
            $found_payee_id = $found_payee->id;
        }
        
        return $found_payee_id;
    }
}
