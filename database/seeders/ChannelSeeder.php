<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;


class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create([
            'name' => 'Shopify',
            'access' => 'Shopify',
            'status' => True,
        ]);

        Channel::create([
            'name' => 'Facebook',
            'access' => 'Facebook',
            'status' => True,
        ]);

        Channel::create([
            'name' => 'Whatsapp',
            'access' => 'Whatsapp',
            'status' => True,
        ]);

        Channel::create([
            'name' => 'MercadoLibre Merchant',
            'access' => 'MercadoLibre Merchant',
            'status' => True,
        ]);

        Channel::create([
            'name' => 'MercadoLibre Fulfillment',
            'access' => 'MercadoLibre Fulfillment',
            'status' => True,
        ]);
    }
}
