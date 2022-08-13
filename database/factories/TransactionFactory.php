<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payee;
use App\Models\Category;

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
        $year = date("Y");
        $rand_date = $this->faker->date();
        $rand_date = $year . substr($rand_date, 4);
        return [
            'trans_date' => $rand_date,
            'payee_id' => Payee::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'orig_detail' => rtrim(strtoupper($this->faker->text(32)),'.'),
            'orig_amt' => $this->faker->numberBetween(0,1000) . '.' . $this->faker->numerify('##'),
            'verified' => rand(0,1),
        ];
    }
}
