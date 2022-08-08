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
        $cats = [
            ['name' => 'Lawn care'],
            ['name' => 'Fuel'],
            ['name' => 'Food'],
            ['name' => 'Entertainment'],
            ['name' => 'Clothing'],
            ['name' => 'Travel'],
            ['name' => 'Furniture'],
            ['name' => 'eMail'],
            ['name' => 'Office Supplies']    
       ];

       foreach ($cats as $cat) {
           Category::create($cat);
       }
    }
}
