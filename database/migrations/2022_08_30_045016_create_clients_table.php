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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('rfc', 15)->unique()->nullable();
            $table->string('phone', 15)->unique(); // key
            $table->string('email', 50)->unique()->nullable();
            $table->boolean('status')->default(true);
            $table->string('street_and_number', 150);
            $table->string('interior_number', 150)->nullable();
            $table->string('colony', 150);
            $table->string('municipality', 150);
            $table->string('postcode', 150);
            $table->string('between_streets', 150)->nullable();
            $table->string('reference', 150)->nullable();
            $table->string('latitude', 150)->nullable();
            $table->string('longitude', 150)->nullable();
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
        Schema::dropIfExists('clients');
    }
};
