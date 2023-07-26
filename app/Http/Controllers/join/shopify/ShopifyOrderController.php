<?php

namespace App\Http\Controllers\join\shopify;

use App\Http\Controllers\Controller;
use App\Http\Controllers\sale\ItemMatchController;
use App\Models\Channel;
use App\Models\Key;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ShopifyOrderController extends Controller
{

    public function getOrders(): \Illuminate\Http\JsonResponse
    {
        $from = date('Y-m-d', strtotime(Carbon::today()->subDays(30)));
        $apiKey = Key::where('channel', 'Shopify')->where('key', 'shopify_api_key')->first()->value;
        $apiSecret = Key::where('channel', 'Shopify')->where('key', 'shopify_api_secret')->first()->value;
        $accessToken = Key::where('channel', 'Shopify')->where('key', 'shopify_access_token')->first()->value;
        $client = new Client(['base_uri' => 'https://' . $apiKey . ':' . $apiSecret . '@lo-quieromx.myshopify.com']);
        $headers = [
            'Accept' => 'application/json',
            'X-Shopify-Access-Token' => $accessToken
        ];
        $uri = '/admin/orders.json?status=any&created_at_min=' . $from . '&limit=250';
        $responseJson = $client->request('GET', $uri, ['headers' => $headers])
            ->getBody()
            ->getContents();
        $response = json_decode($responseJson);
        $this->storeOrders($response->orders);
        return response()->json('ok', 200);
    }

    public function storeOrders($orders)
    {
        $channelId = Channel::where('name', 'Shopify')->first()->id;
        foreach ($orders as $order) {
            $status = 'Pendiente';
            if (isset($order->closed_at)) {
                $status = 'Cerrado';
            } elseif (isset($order->cancelled_at)) {
                $status = 'Cancelado';
            } elseif ($order->financial_status == 'paid') {
                $status = 'Listo para envio';
            } elseif ($order->financial_status == 'authorized') {
                $status = 'Autorizado';
            }
            $conditionOrder = [
                // Example: 4392739864610-#1160
                'marketplace_id' => (string)$order->id . '-' . $order->name
            ];
            $orderData = [
                'order_status' => $status,
                'user_id' => 6, // Seller
                'client_id' => 1, // Mostrador ID
                'payment_type_id' => 1,
                'channel_id' => $channelId,
                'total' => $order->total_price,
                'confirmed' => true,
                'order_at' => date('Y-m-d H:i:s', strtotime($order->created_at))
            ];
            $from = date('Y-m-d 00:00:00', strtotime(Carbon::today()->subDays(40)));
            $to = date('Y-m-d 23:59:59', strtotime(Carbon::today()));
            $saleOrderId = SaleOrder::whereBetween('created_at', [$from, $to])
                ->updateOrCreate($conditionOrder, $orderData)
                ->id;

            $shipPrice = 0;
            foreach ($order->shipping_lines as $shipping) {
                $shipPrice += (float)$shipping->price;
            }
            $itemsCount = sizeof($order->line_items);
            $sippingPrice = $shipPrice / $itemsCount;
            $itemsDesc = $order->total_discounts / $itemsCount;

            foreach ($order->line_items as $orderItem) {
                $upc = !empty($orderItem->ean) ? (string)$orderItem->ean : null;
                $sku = !empty($orderItem->sku) ? (string)$orderItem->sku : null;
                $itemMach = new ItemMatchController();
                $itemMach = $itemMach->itemMatch($upc, $sku);
                // dd($itemMach);
                $conditionOrderItem = [
                    'sale_order_id' => $saleOrderId,
                    'marketplace_item_id' => $orderItem->id,
                ];
                $orderItemData = [
                    // 'sale_order_id' => $saleOrderId,
                    'item_id' => !empty($itemMach['id']) ? $itemMach['id'] : 1,
                    // 'marketplace_item_id' => $orderItem->id,
                    'name' => !empty($itemMach['name']) ? $itemMach['name'] : 'sin-name',
                    'upc' => !empty($itemMach['upc']) ? $itemMach['upc'] : 'sin-upc',
                    'sku' => !empty($itemMach['sku']) ? $itemMach['sku'] : 'sin-sku',
                    'quantity' => !empty($orderItem->quantity) ? $orderItem->quantity : 0,
                    'price' => !empty($itemMach['price']) ? $itemMach['price'] : 0,
                    'cost' => !empty($itemMach['cost']) ? $itemMach['cost'] : 0,
                    'shipping_price' => $sippingPrice,
                    'promotion_discount' => !empty($itemsDesc) ? $itemsDesc : 0,
                ];
                SaleOrderItem::whereBetween('created_at', [$from, $to])
                    ->updateOrCreate($conditionOrderItem, $orderItemData);
            }
        }
    }
}
