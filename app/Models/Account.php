<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "plaid_account_id",
        "mask",
        "name",
        "official_name",
        "subtype",
        "type"
    ];

    public function import_accounts(array $arr) {
        foreach ($arr as $acct) {
            self::create([
                "user_id"           => Auth::user()->id,
                "plaid_account_id"  => $acct->account_id,
                "mask"              => $acct->mask,
                "name"              => $acct->name,
                "official_name"     => $acct->official_name,
                "subtype"           => $acct->subtype,
                "type"              => $acct->type
            ]);
        }
    }
}
