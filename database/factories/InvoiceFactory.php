<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Drug;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
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
            'totalDiscount' => 0,
            'totalProfit' => 0,
            'paid' => 0,
            'date' => $this->faker->dateTimeBetween('2020-1-1', '2030-1-1')->format('Y-m-d'),

        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Invoice $invoice)
        {
            /*  $items = $invoice->invoiceItems;
            $total =  $invoice->invoiceItems->reduce(function ($carry, $item)
            {
                return $carry + ($item['price'] * $item['qty']);
            });
            $totalDiscount = $items->reduce(function ($carry, $item)
            {
                return $carry + ($item['discount'] * $item['qty']);
            });
            $cost = $items->reduce(function ($carry, $item)
            {
                return $carry + ($item['cost'] * $item['qty']);
            });
            $totalProfit = $total - $totalDiscount - $cost;

            $invoice->total = $total;
            $invoice->totalDiscount = $totalDiscount;
            $invoice->totalProfit = $totalProfit;
            $invoice->paid = fake()->numberBetween(0, $total - $totalDiscount);
            $invoice->save(); */
        })->afterCreating(function (Invoice $invoice)
        {
            $items = $invoice->invoiceItems;
            $total =  $invoice->invoiceItems->reduce(function ($carry, $item)
            {
                return $carry + ($item['price'] * $item['qty']);
            }) | 0;
            $totalDiscount = $items->reduce(function ($carry, $item)
            {
                return $carry + ($item['discount'] * $item['qty']);
            }) | 0;
            $cost = $items->reduce(function ($carry, $item)
            {
                return $carry + ($item['cost'] * $item['qty']);
            }) | 0;
            $totalProfit = $total - $totalDiscount - $cost;

            $invoice->total = $total;
            $invoice->totalDiscount = $totalDiscount;
            $invoice->totalProfit = $totalProfit;
            $invoice->paid = fake()->numberBetween(0, $total - $totalDiscount);
            $invoice->save();

            $invoice->pharmacy ? $invoice->pharmacy->increment('safe', $invoice->paid) : null;
        });
    }
}
