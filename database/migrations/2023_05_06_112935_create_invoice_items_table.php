<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId("drug_id")->nullable()->constrained("drugs")->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger("qty");
            $table->date("exp");
            $table->decimal("discount", 8, 3);
            $table->decimal("cost", 8, 3);
            $table->decimal("price", 8, 3);
            $table->foreignId("invoice_id")->nullable()->constrained("invoices")->onUpdate('cascade')->onDelete('cascade');
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
};
