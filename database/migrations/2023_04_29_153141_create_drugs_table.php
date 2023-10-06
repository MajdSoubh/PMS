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
        Schema::create('drugs', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->string('generic');
            $table->string('description')->nullable();
            $table->integer('price');
            $table->integer('sellingCounter')->default(0);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('drugs');
    }
};
