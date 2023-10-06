<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;


class SupplierTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // Create Supplier Tests
    public function test_owner_can_create_supplier()
    {
        $supplier = Supplier::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->actingAs($this->owner)->postJson('/api/supplier',  $supplier);

        $this->assertDatabaseHas('suppliers', $supplier);
        $response->assertStatus(201);
    }
    public function test_purchases_manager_can_create_supplier()
    {
        $supplier = Supplier::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();
        $user = $this->user->assignRole('Purchases Manager');


        $response = $this->actingAs($user)->postJson('/api/supplier',  $supplier);

        $this->assertDatabaseHas('suppliers', $supplier);
        $response->assertStatus(201);
    }
    public function test_guest_cannot_create_supplier()
    {
        $supplier = Supplier::factory()->make()->toArray();

        $response = $this->postJson('/api/supplier',  $supplier);

        $this->assertDatabaseMissing('suppliers', $supplier);
        $response->assertStatus(401);
    }


    // Updating Supplier Tests
    public function test_owner_can_update_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedSupplier =  $supplier;
        $modifiedSupplier['name'] = 'Supplier 1';

        $response = $this->actingAs($this->owner)->putJson('/api/supplier/' . $supplier['id'],  $modifiedSupplier);

        $this->assertDatabaseHas('suppliers', $modifiedSupplier);
        $response->assertStatus(200);
    }
    public function test_purchases_manager_can_update_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedSupplier =  $supplier;
        $modifiedSupplier['name'] = 'Supplier 1';
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->putJson('/api/supplier/' . $supplier['id'],  $modifiedSupplier);

        $this->assertDatabaseHas('suppliers', $modifiedSupplier);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_update_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedSupplier =  $supplier;
        $modifiedSupplier['name'] = 'Supplier 1';

        $response = $this->putJson('/api/supplier/' . $supplier['id'],  $modifiedSupplier);

        $this->assertDatabaseMissing('suppliers', $modifiedSupplier);
        $response->assertStatus(401);
    }
    public function test_voucher_manager_cannot_update_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedSupplier =  $supplier;
        $modifiedSupplier['name'] = 'Supplier 1';
        $this->user->assignRole('Voucher Manager');

        $response = $this->actingAs($this->user)->putJson('/api/supplier/' . $supplier['id'],  $modifiedSupplier);

        $this->assertDatabaseMissing('suppliers', $modifiedSupplier);
        $response->assertStatus(403);
    }

    // Deleting Supplier Tests

    public function test_owner_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();


        $response = $this->actingAs($this->owner)->deleteJson('/api/supplier/' . $supplier['id']);

        $this->assertDatabaseMissing('suppliers', $supplier);
        $response->assertStatus(200);
    }
    public function test_purchases_manager_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/supplier/' . $supplier['id']);

        $this->assertDatabaseMissing('suppliers', $supplier);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->deleteJson('/api/supplier/' . $supplier['id']);

        $this->assertDatabaseHas('suppliers', $supplier);
        $response->assertStatus(401);
    }
    public function test_voucher_manager_cannot_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Voucher Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/supplier/' . $supplier['id']);

        $this->assertDatabaseHas('suppliers', $supplier);
        $response->assertStatus(403);
    }
}
