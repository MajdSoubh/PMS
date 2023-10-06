<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // Create Customer Tests
    public function test_owner_can_create_customer()
    {
        $customer = Customer::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->actingAs($this->owner)->postJson('/api/customer',  $customer);

        $this->assertDatabaseHas('customers', $customer);
        $response->assertStatus(201);
    }
    public function test_sales_manager_can_create_customer()
    {
        $customer = Customer::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();
        $user = $this->user->assignRole('Sales Manager');


        $response = $this->actingAs($user)->postJson('/api/customer',  $customer);

        $this->assertDatabaseHas('customers', $customer);
        $response->assertStatus(201);
    }
    public function test_guest_cannot_create_customer()
    {
        $customer = Customer::factory()->make()->toArray();

        $response = $this->postJson('/api/customer',  $customer);

        $this->assertDatabaseMissing('customers', $customer);
        $response->assertStatus(401);
    }


    // Updating Customer Tests
    public function test_owner_can_update_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedCustomer =  $customer;
        $modifiedCustomer['name'] = 'Customer 1';

        $response = $this->actingAs($this->owner)->putJson('/api/customer/' . $customer['id'],  $modifiedCustomer);

        $this->assertDatabaseHas('customers', $modifiedCustomer);
        $response->assertStatus(200);
    }
    public function test_admin_can_update_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedCustomer =  $customer;
        $modifiedCustomer['name'] = 'Customer 1';
        $this->user->assignRole('Admin');

        $response = $this->actingAs($this->user)->putJson('/api/customer/' . $customer['id'],  $modifiedCustomer);

        $this->assertDatabaseHas('customers', $modifiedCustomer);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_update_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedCustomer =  $customer;
        $modifiedCustomer['name'] = 'Customer 1';

        $response = $this->putJson('/api/customer/' . $customer['id'],  $modifiedCustomer);

        $this->assertDatabaseMissing('customers', $modifiedCustomer);
        $response->assertStatus(401);
    }
    public function test_purchase_manager_cannot_update_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedCustomer =  $customer;
        $modifiedCustomer['name'] = 'Customer 1';
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->putJson('/api/customer/' . $customer['id'],  $modifiedCustomer);

        $this->assertDatabaseMissing('customers', $modifiedCustomer);
        $response->assertStatus(403);
    }

    // Deleting Customer Tests

    public function test_owner_can_delete_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();


        $response = $this->actingAs($this->owner)->deleteJson('/api/customer/' . $customer['id']);

        $this->assertDatabaseMissing('customers', $customer);
        $response->assertStatus(200);
    }
    public function test_sales_manager_can_delete_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Sales Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/customer/' . $customer['id']);

        $this->assertDatabaseMissing('customers', $customer);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_delete_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->deleteJson('/api/customer/' . $customer['id']);

        $this->assertDatabaseHas('customers', $customer);
        $response->assertStatus(401);
    }
    public function test_purchase_manager_cannot_delete_customer()
    {
        $customer = Customer::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/customer/' . $customer['id']);

        $this->assertDatabaseHas('customers', $customer);
        $response->assertStatus(403);
    }
}
