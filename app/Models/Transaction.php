<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "user_id",
        "trans_date",
        "payee_id",
        "category_id",
        "orig_detail",
        "orig_amt",
        "approved",
    ];

    use HasFactory;
}
