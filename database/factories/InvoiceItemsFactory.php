<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\InvoiceItems;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItems>
 */
class InvoiceItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'qty' => $this->faker->numberBetween(1, 4),
            'exp' => $this->faker->dateTimeBetween('2018-01-01', now())->format('Y-m-d'),
            'discount' => $this->faker->randomFloat(1, 100, 299),
            'cost' => $this->faker->randomFloat(1, 300, 1000),
            'price' => $this->faker->randomFloat(1, 1000, 3000),


        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (InvoiceItems $item)
        {
            /*     $item->drug()->increment('sellingCounter', $item->qty); */
        })->afterCreating(function (InvoiceItems $item)
        {
            $item->drug()->increment('sellingCounter', $item->qty);
        });
    }
}
