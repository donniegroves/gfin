<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;

class Category extends Model
{
    protected $fillable = [
        "user_id",
        "name",
    ];
    
    use HasFactory;

    public function getOrCreateFromString(string $string): int|bool {
        // search for payee
        $all_cats = (new CategoryController())->index()->pluck("name","id")->toArray();
        $found_cat_id = array_search($string, $all_cats);

        // adding payee if it doesn't exist.
        if (!$found_cat_id) {
            $new_request = new Request();
            $new_request->category =
                    ['name' => $string];
            $found_category = (new CategoryController())->store($new_request);
            $found_cat_id = $found_category->id;
        }
        
        return $found_cat_id;
    }
}
