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
        $payee_names = [
            'Allegiant',
            'BearStore',
            'Google',
            'Ikea',
            'InstaCart',
            'Netflix',
            'Publix',
            'RaceTrac',
            'TruGreen'
       ];

       foreach ($payee_names as $name) {
            Payee::create([
                'user_id'   => 10,
                'name'      => $name
            ]);
       }
    }
}
