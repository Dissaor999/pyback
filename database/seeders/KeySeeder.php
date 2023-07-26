<?php

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Seeder;

class KeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // :::::::::::::::::::::: Shopify
        Key::create([
            'channel' => 'Shopify',
            'name' => 'API',
            'key' => 'shopify_api_key',
            'value' => '1e8e7912189fb7f1e02c71ee44440e60',
        ]);

        Key::create([
            'channel' => 'Shopify',
            'name' => 'API',
            'key' => 'shopify_api_secret',
            'value' => '74a26efdf1f93a439ebf88e85d95b9ad',
        ]);

        Key::create([
            'channel' => 'Shopify',
            'name' => 'API',
            'key' => 'shopify_access_token',
            'value' => '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',
        ]);

        // :::::::::::::::::::::: Zoho
        Key::create([
            'channel' => 'Zoho',
            'name' => 'JSON',
            'key' => 'client_id',
            'value' => '1000.Q1PRS41BLAMGFVD1ZFIH3BO2ZGN36C',
        ]);

        Key::create([
            'channel' => 'Zoho',
            'name' => 'JSON',
            'key' => 'secret_id',
            'value' => '81e5721d7836151261c9cdebf8feda066823afc86a',
        ]);

        Key::create([
            'channel' => 'Zoho',
            'name' => 'JSON',
            'key' => 'oAuth',
            'value' => '000000',
        ]);

        Key::create([
            'channel' => 'Zoho',
            'name' => 'JSON',
            'key' => 'refresh',
            'value' => '111111',
        ]);

        Key::create([
            'channel' => 'Listo',
            'name' => 'Token',
            'key' => 'token_listo',
            'value' => '1e71fe81fa107d567be8046afd73b1e832c9a350',
        ]);
    }
}
