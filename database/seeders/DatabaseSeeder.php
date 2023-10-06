<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Drug;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Pharmacy;
use App\Models\PurchaseItems;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $ph = Pharmacy::factory()->create();

        $user = User::factory(1)->for($ph)->create(['email' => 'm@m', 'isOwner' => true]);

        $suppliers = Supplier::factory(10)->for($ph)->create();

        $customers = Customer::factory(10)->for($ph)->create();

        $drugs = Drug::factory(16)->has(Stock::factory(fake()->numberBetween(1, 3))->for($ph))->for($ph)->create();

        Invoice::factory(6)
            ->has(
                InvoiceItems::factory(fake()->numberBetween(1, 3))->sequence(function ($sequence)
                {
                    return ['drug_id' => Drug::inRandomOrder()->first()];
                })
            )
            ->for($ph)
            ->sequence(function ($sequence)
            {
                return ['customer_id' => Customer::inRandomOrder()->first()];
            })
            ->create();


        Purchase::factory(6)
            ->for($ph)
            ->has(
                PurchaseItems::factory(fake()->numberBetween(1, 3))->sequence(
                    function ($sequence)
                    {
                        return ['drug_id' => Drug::inRandomOrder()->first()];
                    }
                )
            )
            ->sequence(function ($sequence)
            {
                return ['supplier_id' => Supplier::inRandomOrder()->first()];
            })
            ->create();


        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
