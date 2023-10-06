<?php

namespace Tests\Feature;

use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\PurchaseItems;
use App\Models\Drug;
use App\Models\Supplier;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private function create_purchase($persist): array
    {
        $supplier = Supplier::factory()->for($this->pharmacy)->create();
        $drug = Drug::factory()->for($this->pharmacy)->create();
        $qty = 1;
        $cost = 300;
        $paid = 0;
        $purchase = [];
        if ($persist)
        {

            $purchase = Purchase::factory()
                ->for($this->pharmacy)
                ->has(
                    PurchaseItems::factory()->state(['drug_id' => $drug->id, 'qty' => $qty, 'cost' => $cost])
                )
                ->create(['supplier_id' => $supplier->id])->toArray();
            unset($purchase['purchase_items']);
            unset($purchase['pharmacy']);
        }
        else
        {
            $purchase = Purchase::factory()->make()->toArray();
            $purchase['supplier'] = $supplier->id;
            $purchase['total'] = $qty * $cost;
            $purchase['paid'] = $paid;
            $purchase['items'][] = PurchaseItems::factory()->state(['qty' => $qty, 'cost' => $cost])->make()->toArray();
            $purchase['items'][0]['drug'] = $drug->id;
        }
        return $purchase;
    }
    // Create Purchases Tests
    public function test_owner_can_create_purchase()
    {
        $purchase = $this->create_purchase(0);
        $savedPurchase = $purchase;
        $savedPurchase['supplier_id'] = $purchase['supplier'];
        unset($savedPurchase['items']);
        unset($savedPurchase['supplier']);

        $response = $this->actingAs($this->owner)->postJson('/api/purchase',  $purchase);

        $this->assertDatabaseHas('purchases', $savedPurchase);
        $response->assertStatus(200);
    }
    public function test_purchases_manager_can_create_purchase()
    {
        $purchase = $this->create_purchase(0);
        $savedPurchase = $purchase;
        $savedPurchase['supplier_id'] = $purchase['supplier'];
        unset($savedPurchase['items']);
        unset($savedPurchase['supplier']);
        $user = $this->user->assignRole('Purchases Manager');


        $response = $this->actingAs($user)->postJson('/api/purchase',  $purchase);

        $this->assertDatabaseHas('purchases', $savedPurchase);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_create_purchase()
    {
        $purchase = $this->create_purchase(0);
        $savedPurchase = $purchase;
        $savedPurchase['supplier_id'] = $purchase['supplier'];
        unset($savedPurchase['items']);
        unset($savedPurchase['supplier']);

        $response = $this->postJson('/api/purchase',  $purchase);

        $this->assertDatabaseMissing('purchases', $savedPurchase);
        $response->assertStatus(401);
    }


    // Updating Purchases Tests
    public function test_owner_can_update_purchase()
    {
        $purchase = $this->create_purchase(1);
        $modifiedPurchase = $purchase;
        $modifiedPurchase['paid'] = 1111.11;

        $response = $this->actingAs($this->owner)->putJson('/api/purchase/' . $purchase['id'],  $modifiedPurchase);

        $this->assertDatabaseHas('purchases', $modifiedPurchase);
        $response->assertStatus(200);
    }
    public function test_admin_can_update_purchase()
    {
        $purchase = $this->create_purchase(1);
        $modifiedPurchase = $purchase;
        $modifiedPurchase['paid'] = 1111.11;
        $this->user->assignRole('Admin');

        $response = $this->actingAs($this->user)->putJson('/api/purchase/' . $purchase['id'],  $modifiedPurchase);

        $this->assertDatabaseHas('purchases', $modifiedPurchase);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_update_purchase()
    {
        $purchase = $this->create_purchase(1);
        $modifiedPurchase = $purchase;
        $modifiedPurchase['paid'] = 1111.11;
        $response = $this->putJson('/api/purchase/' . $purchase['id'],  $modifiedPurchase);

        $this->assertDatabaseMissing('purchases', $modifiedPurchase);
        $response->assertStatus(401);
    }
    public function test_sales_manager_cannot_update_purchase()
    {
        $purchase = $this->create_purchase(1);
        $modifiedPurchase = $purchase;
        $modifiedPurchase['paid'] = 1111.11;
        $this->user->assignRole('Sales Manager');

        $response = $this->actingAs($this->user)->putJson('/api/purchase/' . $purchase['id'],  $modifiedPurchase);

        $this->assertDatabaseMissing('purchases', $modifiedPurchase);
        $response->assertStatus(403);
    }

    // Deleting Purchases Tests

    public function test_owner_can_delete_purchase()
    {
        $purchase = $this->create_purchase(1);

        $response = $this->actingAs($this->owner)->deleteJson('/api/purchase/' . $purchase['id']);

        $this->assertDatabaseMissing('purchases', $purchase);
        $response->assertStatus(200);
    }
    public function test_purchases_manager_can_delete_purchase()
    {
        $purchase = $this->create_purchase(1);
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/purchase/' . $purchase['id']);

        $this->assertDatabaseMissing('purchases', $purchase);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_delete_purchase()
    {
        $purchase = $this->create_purchase(1);

        $response = $this->deleteJson('/api/purchase/' . $purchase['id']);

        $this->assertDatabaseHas('purchases', $purchase);
        $response->assertStatus(401);
    }
    public function test_sales_manager_cannot_delete_purchase()
    {
        $purchase = $this->create_purchase(1);
        $this->user->assignRole('Sales Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/purchase/' . $purchase['id']);

        $this->assertDatabaseHas('purchases', $purchase);
        $response->assertStatus(403);
    }
}
