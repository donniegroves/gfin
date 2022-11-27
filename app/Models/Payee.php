<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
    protected $fillable = [
        "user_id",
        "name",
    ];

    use HasFactory;
}
