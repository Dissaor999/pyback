<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create([
            'name' => 'Tarjeta',
            'sat_code' => 'sat_c ' . Str::random(3),
        ]);
        PaymentType::create([
            'name' => 'Efectivo',
            'sat_code' => 'sat_c ' . Str::random(3),
        ]);
        PaymentType::create([
            'name' => 'Transferencia',
            'sat_code' => 'sat_c ' . Str::random(3),
        ]);
    }
}
