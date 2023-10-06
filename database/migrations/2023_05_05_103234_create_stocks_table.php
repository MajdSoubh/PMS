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
        Schema::create('stocks', function (Blueprint $table)
        {
            $table->id();
            $table->date("mfd");
            $table->date("exp");
            $table->foreignId('drug_id')->nullable()->constrained('drugs')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger("qty")->default(0);
            $table->decimal("cost", 8, 3)->nullable();
            $table->foreignId('pharmacy_id')->nullable()->constrained('pharmacies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
