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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_order_id')->constrained('sale_orders');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('channel_id')->constrained('channels');
            $table->string('payment_method', 20);
            $table->string('uuid', 500)->unique()->nullable();
            $table->string('description', 500)->nullable();
            $table->decimal('total', 8, 2);
            $table->string('invoice_status', 2)->nullable()->default(1);
            $table->string('url_xml', 1000)->nullable();
            $table->string('url_pdf', 1000)->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
