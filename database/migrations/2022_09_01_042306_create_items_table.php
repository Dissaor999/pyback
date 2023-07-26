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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('zoho_id')->unique();
            $table->string('name', 500);
            $table->string('upc', 15)->unique()->nullable();
            $table->string('sku', 15)->unique();
            $table->string('sat_code', 15)->nullable();
            $table->string('product_type', 50)->nullable();
            $table->boolean('status')->default(true);
            $table->integer('total_stock')->nullable()->default(0);
            $table->decimal('commission', 8, 2)->nullable()->default(0.0);
            $table->decimal('cost', 8, 2)->nullable()->default(0.0);
            $table->decimal('price', 8, 2)->nullable()->default(0.0);
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
        Schema::dropIfExists('items');
    }
};
