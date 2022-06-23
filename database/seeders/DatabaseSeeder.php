<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payee;
use App\Models\PayeePattern;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Payee::factory(7)->create();
        PayeePattern::factory(3)->create();
        Transaction::factory(100)->create();
    }
}
