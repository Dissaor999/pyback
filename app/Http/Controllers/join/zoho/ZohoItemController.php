<?php

namespace App\Http\Controllers\join\zoho;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Key;
use GuzzleHttp\Client;

class ZohoItemController extends Controller
{
    public function __construct()
    {
        $this->client = Key::where([['channel', 'zoho'], ['key', 'client_id']])->first()->value;
        $this->secret = Key::where([['channel', 'zoho'], ['key', 'secret_id']])->first()->value;
        $this->auth = Key::where([['channel', 'zoho'], ['key', 'oAuth']])->first()->value;
        $this->refresh = Key::where([['channel', 'zoho'], ['key', 'refresh']])->first()->value;
    }

    public function getZohoItems()
    {
        //RefreshToken
        $refreshToken = new \App\Http\Controllers\join\zoho\ZohoConnectionController();
        $refreshToken->refreshTokenZoho();
        $page = 0;
        $client = new Client(['headers' => [
            'X-Foo' => 'Bar',
            'x-format-new' => 'true',
            'Authorization' => 'Zoho-oauthtoken ' . $this->auth,
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ]]);
        $responseJson = $client->request('GET', "https://inventory.zoho.com/api/v1/items?organization_id=785282661")
            ->getBody()->getContents();
        $response = json_decode($responseJson);
        $this->sotreZohoItems($response->items);
        while ($response->page_context->has_more_page == true) {
            $page++;
            $responseJson = $client->request('GET', "https://inventory.zoho.com/api/v1/items?page=" . ($page) . "&per_page=200&organization_id=785282661")
                ->getBody()->getContents();
            if (fmod($page, 5) == 0) {
                $refreshToken->refreshTokenZoho();
            }
            $this->sotreZohoItems($response->items);
        }
        return response()->json(['success' => 'Productos Actualizados!' . ($page)]);
    }

    public function sotreZohoItems($items)
    {
        foreach ($items as $item) {
            Item::updateOrCreate(
                ['zoho_id' => $item->item_id],
                [
                    'sku' => $item->sku,
                    'name' => !empty($item->name) ? (string)$item->name : NULL,
                    'upc' => !empty($item->upc) ? (string)$item->upc : NULL,
                    'product_type' => !empty($item->item_type) ? (string)$item->item_type : NULL,
                    'status' => !empty($item->status) ? 1 : 0,
                    'cost' => !empty($item->purchase_rate) ? $item->purchase_rate : 0,
                    'price' => !empty($item->rate) ? $item->rate : 0,
                    'total_stock' => !empty($item->stock_on_hand) ? $item->stock_on_hand : 0,
                    'sat_code' => !empty($item->cf_sat_code) ? (string)$item->cf_sat_code : NULL
                ]
            );
        }
    }
}
