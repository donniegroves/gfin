<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payee;
use App\Models\PayeePattern;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\CategoryPattern;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        // Payee::factory(7)->create();
        $this->call(PayeeSeeder::class);
        PayeePattern::factory(3)->create();
        // Category::factory(12)->create();
        $this->call(CategorySeeder::class);
        CategoryPattern::factory(4)->create();
        Transaction::factory(100)->create();
    }
}
