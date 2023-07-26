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
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('channel_id')->constrained('channels');
            $table->string('marketplace_id', 250)->unique();
            $table->decimal('total', $precision = 8, $scale = 2);
            $table->boolean('confirmed')->default(false);
            $table->string('order_status', 50)->default('Manual');
            $table->boolean('is_fee_retrieve')->default(false);
            $table->decimal('commission', 8, 2)->nullable()->default(0.0);
            $table->string('description', 500)->nullable();
            $table->boolean('invoice_status')->default(false);
            $table->timestamp('shipping_at')->nullable();
            $table->timestamp('order_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('sale_orders');
    }
};
