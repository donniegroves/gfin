<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ImportHistory extends Model
{
    use HasFactory;

    public const TYPES = [
        "csv"   => 1,
        "plaid" => 2
    ];

    protected $fillable = [
        "user_id",
        "import_type",
        "total_incoming",
        "existing_skipped",
        "new_processed",
        "matched_trans",
        "unmatched_trans",
        "start_date",
        "end_date"
    ];

    protected function importType(): Attribute {
        return Attribute::make(
            get: fn ($value) => array_search($value,self::TYPES)
        );
    }

    public static function add($input) {
        self::create([
            "user_id"           => $input['user_id'],
            "import_type"       => $input['import_type'],
            "total_incoming"    => $input['total_incoming'],
            "existing_skipped"  => $input['existing_skipped'],
            "new_processed"     => $input['new_processed'],
            "matched_trans"     => $input['matched_trans'],
            "unmatched_trans"   => $input['unmatched_trans'],
            "start_date"        => $input['start_date'] ?? null,
            "end_date"          => $input['end_date'] ?? null
        ]);
    }

    public function get() {
        return self::where('user_id', Auth::user()->id)->get();
    }
}
