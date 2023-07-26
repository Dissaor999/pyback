<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'zoho_id' => 1,
            'name' => 'Producto vacio (sin poder mapear)',
            'upc' => 'sin-upc',
            'sku' => 'sin-sku',
            'sat_code' => 'sin-codigo',
            'product_type' => 'Inventario',
            'status' => false,
            'total_stock' => 0,
            'commission' => 0,
            'cost' => 0,
            'price' => 0,
        ]);
    }
}
