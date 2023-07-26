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
        Schema::create('sale_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_order_id')->constrained('sale_orders')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->string('marketplace_item_id', 100)->nullable();
            $table->string('name', 500);
            $table->string('upc', 15);
            $table->string('sku', 15);
            $table->integer('quantity');
            $table->decimal('cost', 8, 2)->nullable()->default(0.0);
            $table->decimal('price', 8, 2);
            $table->decimal('tax', 8, 2)->nullable()->default(0.0);
            $table->decimal('shipping_price', 8, 2)->nullable()->default(0.0);
            $table->decimal('shipping_tax', 8, 2)->nullable()->default(0.0);
            $table->decimal('shipping_discount', 8, 2)->nullable()->default(0.0);
            $table->decimal('gift_wrap_price', 8, 2)->nullable()->default(0.0);
            $table->decimal('gift_wrap_tax', 8, 2)->nullable()->default(0.0);
            $table->decimal('gift_wrap_commission', 8, 2)->nullable()->default(0.0);
            $table->decimal('discount', 8, 2)->nullable()->default(0.0);
            $table->decimal('commission', 8, 2)->nullable()->default(0.0);
            $table->decimal('item_total', 8, 2)->nullable()->default(0.0);
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
        Schema::dropIfExists('sale_order_items');
    }
};
