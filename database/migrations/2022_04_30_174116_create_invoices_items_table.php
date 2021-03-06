<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_items', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId("medicine_id")->constrained("medicines")->onUpdate('cascade')->onDelete('cascade');;
            $table->bigInteger("qty");
            $table->date("exp");
            $table->decimal("discount", 8, 3);
            $table->foreignId("invoice_id")->constrained("invoices")->onUpdate('cascade')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
