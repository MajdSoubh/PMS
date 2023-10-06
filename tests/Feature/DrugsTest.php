<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Drug;


class DrugsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Create Drug Tests
    public function test_owner_can_create_drug()
    {
        $drug = Drug::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->actingAs($this->owner)->postJson('/api/drug',  $drug);

        $this->assertDatabaseHas('drugs', $drug);
        $response->assertStatus(201);
    }
    public function test_inventory_manager_can_create_drug()
    {
        $drug = Drug::factory()->make(['pharmacy_id' => $this->pharmacy])->toArray();
        $user = $this->user->assignRole('Inventory Manager');


        $response = $this->actingAs($user)->postJson('/api/drug',  $drug);

        $this->assertDatabaseHas('drugs', $drug);
        $response->assertStatus(201);
    }
    public function test_guest_cannot_create_drug()
    {
        $drug = Drug::factory()->make()->toArray();

        $response = $this->postJson('/api/drug',  $drug);

        $this->assertDatabaseMissing('drugs', $drug);
        $response->assertStatus(401);
    }


    // Updating Drug Tests
    public function test_owner_can_update_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedDrug =  $drug;
        $modifiedDrug['name'] = 'Drug 1';

        $response = $this->actingAs($this->owner)->putJson('/api/drug/' . $drug['id'],  $modifiedDrug);

        $this->assertDatabaseHas('drugs', $modifiedDrug);
        $response->assertStatus(200);
    }
    public function test_admin_can_update_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedDrug =  $drug;
        $modifiedDrug['name'] = 'Drug 1';
        $this->user->assignRole('Admin');

        $response = $this->actingAs($this->user)->putJson('/api/drug/' . $drug['id'],  $modifiedDrug);

        $this->assertDatabaseHas('drugs', $modifiedDrug);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_update_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedDrug =  $drug;
        $modifiedDrug['name'] = 'Drug 1';

        $response = $this->putJson('/api/drug/' . $drug['id'],  $modifiedDrug);

        $this->assertDatabaseMissing('drugs', $modifiedDrug);
        $response->assertStatus(401);
    }
    public function test_purchase_manager_cannot_update_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $modifiedDrug =  $drug;
        $modifiedDrug['name'] = 'Drug 1';
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->putJson('/api/drug/' . $drug['id'],  $modifiedDrug);

        $this->assertDatabaseMissing('drugs', $modifiedDrug);
        $response->assertStatus(403);
    }

    // Deleting Drug Tests

    public function test_owner_can_delete_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();


        $response = $this->actingAs($this->owner)->deleteJson('/api/drug/' . $drug['id']);

        $this->assertDatabaseMissing('drugs', $drug);
        $response->assertStatus(200);
    }
    public function test_inventory_manager_can_delete_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Inventory Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/drug/' . $drug['id']);

        $this->assertDatabaseMissing('drugs', $drug);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_delete_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();

        $response = $this->deleteJson('/api/drug/' . $drug['id']);

        $this->assertDatabaseHas('drugs', $drug);
        $response->assertStatus(401);
    }
    public function test_purchase_manager_cannot_delete_drug()
    {
        $drug = Drug::factory()->create(['pharmacy_id' => $this->pharmacy])->toArray();
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/drug/' . $drug['id']);

        $this->assertDatabaseHas('drugs', $drug);
        $response->assertStatus(403);
    }
}
