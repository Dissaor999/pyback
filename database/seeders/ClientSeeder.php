<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// Model

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'name' => 'Mostrador General',
                'email' => 'MostradorGeneral@hermes.mx',
            ],
            [
                'name' => 'Mostrador Shopify',
                'email' => 'MostradorShopify@hermes.mx',
            ],
            [
                'name' => 'Mostrador Facebook',
                'email' => 'MostradorFacebook@hermes.mx',
            ],
            [
                'name' => 'Mostrador Whatsapp',
                'email' => 'MostradorWhatsapp@hermes.mx',
            ],
            [
                'name' => 'Mostrador Meli Merchant',
                'email' => 'MostradorMeliMerch@hermes.mx',
            ],
            [
                'name' => 'Mostrador Meli Fulfillment',
                'email' => 'MostradorMeluFull@hermes.mx',
            ]
        ];

        foreach ($clients as $client) {
            $defaultData = [
                'rfc' => 'rfc ' . Str::random(5),
                'phone' => rand(11111111, 99999999),
                'status' => True,
                'street_and_number' => 'street #' . rand(11111111, 99999999),
                'interior_number' => rand(11111111, 99999999),
                'colony' => 'colony',
                'municipality' => 'municipality',
                'postcode' => 'postcode',
                'between_streets' => 'between streets',
                'reference' => 'reference',
                'latitude' => 'latitude',
                'longitude' => 'longitude'
            ];
            $clientData = array_merge($client, $defaultData);
            Client::create($clientData);
        }

    }
}
