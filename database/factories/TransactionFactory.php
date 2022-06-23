<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $rand_year = rand(0,1) ? '2021' : '2022';
        $rand_date = $this->faker->date();
        $rand_date = $rand_year . substr($rand_date, 4);
        return [
            'trans_date' => $rand_date,
            'payee_id' => Payee::all()->random()->id,
            'description' => rtrim(strtoupper($this->faker->text(32)),'.'),
            'amount' => $this->faker->numberBetween(0,1000) . '.' . $this->faker->numerify('##'),
            'verified' => rand(0,1),
        ];
    }
}
