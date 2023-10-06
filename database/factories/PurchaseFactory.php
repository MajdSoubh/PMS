<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\PurchaseItems;
use App\Models\Drug;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'total' => 0,
            'paid' => 0,
            'date' => $this->faker->dateTimeBetween('2020-1-1', '2030-1-1')->format('Y-m-d'),

        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Purchase $purchase)
        {
            /*          $items = $purchase->purchaseItems;
            $total = $items->sum('cost');

            $purchase->total = $total;
            $purchase->paid = fake()->numberBetween(0, $total);
            $purchase->save(); */
        })->afterCreating(function (Purchase $purchase)
        {
            $items = $purchase->purchaseItems;
            $total = $items->sum('cost') | 0;

            $purchase->total = $total;
            $purchase->paid = fake()->numberBetween(0, $total);
            $purchase->save();

            $purchase->pharmacy ? $purchase->pharmacy->decrement('safe', $purchase->paid) : null;
        });
    }
}
