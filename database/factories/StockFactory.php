<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "mfd" => fake()->dateTimeBetween('2018-01-01', now())->format('Y-m-d'),
            "exp" => fake()->dateTimeBetween('2023-01-01', '2028-01-01')->format('Y-m-d'),
            'qty' => fake()->numberBetween(1, 22),
            'cost' => fake()->randomFloat(1, 100, 1000),
        ];
    }
}
