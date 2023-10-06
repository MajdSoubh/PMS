<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\InvoiceItems;
use App\Models\Stock;
use Carbon\Carbon;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // Create Invoice Tests
    private function create_invoice($persist): array
    {
        $cost = 300;
        $price = 400;
        $exp = Carbon::now()->subYear(2)->format('Y-m-d');
        $drug = Drug::factory()->has(Stock::factory()->state(['exp' => $exp, 'qty' => 2, 'cost' => $cost])->for($this->pharmacy))->for($this->pharmacy)->create();
        $invoice = [];
        if ($persist)
        {
            $invoice = Invoice::factory()
                ->has(
                    InvoiceItems::factory()->state(['drug_id' => $drug->id, 'qty' => 1, 'exp' => $exp, 'cost' => $cost, 'discount' => 0, 'price' => $price])
                )
                ->for($this->pharmacy)
                ->create()->toArray();

            unset($invoice['invoice_items']);
            unset($invoice['pharmacy']);
        }
        else
        {

            $invoice = Invoice::factory()
                ->make(['paid' => 0])
                ->toArray();
            $invoice['items'][] = InvoiceItems::factory()->make(['qty' => 1, 'exp' => $exp, 'cost' => $cost, 'discount' => 0, 'price' => $price])->toArray();
            $invoice['items'][0]['drug'] = $drug->id;
            $invoice['total'] = $price;
            $invoice['totalProfit'] = $price - $cost;
        }
        return $invoice;
    }
    public function test_owner_can_create_invoice()
    {

        $invoice = $this->create_invoice(0);
        $savedInvoice = $invoice;
        unset($savedInvoice['items']);

        $response = $this->actingAs($this->owner)->postJson('/api/invoice',  $invoice);

        $this->assertDatabaseHas('invoices', $savedInvoice);
        $response->assertStatus(200);
    }
    public function test_sales_manager_can_create_invoice()
    {
        $invoice = $this->create_invoice(0);
        $savedInvoice = $invoice;
        unset($savedInvoice['items']);
        $user = $this->user->assignRole('Sales Manager');


        $response = $this->actingAs($user)->postJson('/api/invoice',  $invoice);

        $this->assertDatabaseHas('invoices', $savedInvoice);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_create_invoice()
    {
        $invoice = $this->create_invoice(0);
        $savedInvoice = $invoice;
        unset($savedInvoice['items']);

        $response = $this->postJson('/api/invoice',  $invoice);

        $this->assertDatabaseMissing('invoices', $savedInvoice);
        $response->assertStatus(401);
    }


    // Updating Invoice Tests
    public function test_owner_can_update_invoice()
    {
        $invoice = $this->create_invoice(1);
        $modifiedInvoice = $invoice;
        $modifiedInvoice['total'] = 1111.11;

        $response = $this->actingAs($this->owner)->putJson('/api/invoice/' . $invoice['id'],  $modifiedInvoice);

        $this->assertDatabaseHas('invoices', $modifiedInvoice);
        $response->assertStatus(200);
    }
    public function test_admin_can_update_invoice()
    {
        $invoice = $this->create_invoice(1);
        $modifiedInvoice = $invoice;
        $modifiedInvoice['total'] = 1111.11;
        $this->user->assignRole('Admin');

        $response = $this->actingAs($this->user)->putJson('/api/invoice/' . $invoice['id'],  $modifiedInvoice);

        $this->assertDatabaseHas('invoices', $modifiedInvoice);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_update_invoice()
    {
        $invoice = $this->create_invoice(1);
        $modifiedInvoice = $invoice;
        $modifiedInvoice['total'] = 1111.11;

        $response = $this->putJson('/api/invoice/' . $invoice['id'],  $modifiedInvoice);

        $this->assertDatabaseMissing('invoices', $modifiedInvoice);
        $response->assertStatus(401);
    }
    public function test_purchase_manager_cannot_update_invoice()
    {
        $invoice = $this->create_invoice(1);
        $modifiedInvoice = $invoice;
        $modifiedInvoice['total'] = 1111.11;
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->putJson('/api/invoice/' . $invoice['id'],  $modifiedInvoice);

        $this->assertDatabaseMissing('invoices', $modifiedInvoice);
        $response->assertStatus(403);
    }

    // Deleting Invoice Tests

    public function test_owner_can_delete_invoice()
    {
        $invoice = $this->create_invoice(1);

        $response = $this->actingAs($this->owner)->deleteJson('/api/invoice/' . $invoice['id']);

        $this->assertDatabaseMissing('invoices', $invoice);
        $response->assertStatus(200);
    }
    public function test_sales_manager_can_delete_invoice()
    {
        $invoice = $this->create_invoice(1);
        $this->user->assignRole('Sales Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/invoice/' . $invoice['id']);

        $this->assertDatabaseMissing('invoices', $invoice);
        $response->assertStatus(200);
    }
    public function test_guest_cannot_delete_invoice()
    {
        $invoice = $this->create_invoice(1);

        $response = $this->deleteJson('/api/invoice/' . $invoice['id']);

        $this->assertDatabaseHas('invoices', $invoice);
        $response->assertStatus(401);
    }



    public function test_purchase_manager_cannot_delete_invoice()
    {
        $invoice = $this->create_invoice(1);
        $this->user->assignRole('Purchases Manager');

        $response = $this->actingAs($this->user)->deleteJson('/api/invoice/' . $invoice['id']);

        $this->assertDatabaseHas('invoices', $invoice);
        $response->assertStatus(403);
    }
}
