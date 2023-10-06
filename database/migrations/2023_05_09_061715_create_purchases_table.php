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
        Schema::create('purchases', function (Blueprint $table)
        {
            $table->id();
            $table->decimal("total", 15, 3);
            $table->decimal('paid', 15, 3)->default(0);
            $table->decimal('rest', 15, 3)->virtualAs("total-paid");
            $table->foreignId("supplier_id")->nullable()->constrained("suppliers")->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('pharmacy_id')->nullable()->constrained('pharmacies')->onUpdate('cascade')->onDelete('cascade');
            $table->date("date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
