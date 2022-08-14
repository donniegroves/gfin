<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cat_names = [
            'Lawn care',
            'Fuel',
            'Food',
            'Entertainment',
            'Clothing',
            'Travel',
            'Furniture',
            'eMail',
            'Office Supplies' 
       ];

       foreach ($cat_names as $name) {
           Category::create(
            [
                'user_id'   => 10,
                'name'      => $name
            ]
           );
       }
    }
}
