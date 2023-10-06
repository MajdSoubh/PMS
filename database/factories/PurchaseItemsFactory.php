<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseItems>
 */
class PurchaseItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'qty' => $this->faker->numberBetween(1, 40),
            'mfd' => $this->faker->dateTimeBetween('2018-01-01', now())->format('Y-m-d'),
            'exp' => $this->faker->dateTimeBetween('2018-01-01', '2030-01-01')->format('Y-m-d'),
            'cost' => $this->faker->randomFloat(1, 100, 1000),

        ];
    }
}
