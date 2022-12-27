<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "total_incoming",
        "existing_skipped",
        "new_processed",
        "matched_trans",
        "unmatched_trans",
        "start_date",
        "end_date"
    ];

    public static function add($input) {
        self::create([
            "user_id"           => $input['user_id'],
            "total_incoming"    => $input['total_incoming'],
            "existing_skipped"  => $input['existing_skipped'],
            "new_processed"     => $input['new_processed'],
            "matched_trans"     => $input['matched_trans'],
            "unmatched_trans"   => $input['unmatched_trans'],
            "start_date"        => $input['start_date'] ?? null,
            "end_date"          => $input['end_date'] ?? null
        ]);
    }
}
