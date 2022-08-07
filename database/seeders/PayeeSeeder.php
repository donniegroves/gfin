<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payee;

class PayeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payees = [
            ['name' => 'Allegiant'],
            ['name' => 'BearStore'],
            ['name' => 'Google'],
            ['name' => 'Ikea'],
            ['name' => 'InstaCart'],
            ['name' => 'Netflix'],
            ['name' => 'Publix'],
            ['name' => 'RaceTrac'],
            ['name' => 'TruGreen']    
       ];

       foreach ($payees as $payee) {
           Payee::create($payee);
       }
    }
}
