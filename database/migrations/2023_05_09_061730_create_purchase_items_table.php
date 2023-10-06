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
        Schema::create('purchase_items', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId("drug_id")->nullable()->constrained("drugs")->onUpdate('cascade')->onDelete('cascade');;
            $table->bigInteger("qty");
            $table->decimal('cost', 8, 3);
            $table->foreignId("purchase_id")->nullable()->constrained("purchases")->onUpdate('cascade')->onDelete('cascade');;
            $table->date("mfd");
            $table->date("exp");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
};
