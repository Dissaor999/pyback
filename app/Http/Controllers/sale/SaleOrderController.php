<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Item;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class SaleOrderController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $saleOrders = SaleOrder::with('getUser')
            ->with('getClient')
            ->with('getPaymentType')
            ->with('getChannel')
            ->get();

        $orders = [];
        $date = Carbon::now();
        foreach ($saleOrders as $o) {
            $lastActivity = $date->createFromTimeStamp(strtotime($o['updated_at']))->diffForHumans();
            $data['id'] = $o->id;
            $data['user_id'] = $o->getUser->name;
            $data['client_id'] = $o->getClient->name;
            $data['payment_type_id'] = $o->getPaymentType->name;
            $data['channel_id'] = $o->getChannel->name;
            $data['marketplace_id'] = $o->marketplace_id;
            $data['total'] = $o->total;
            $data['confirmed'] = $o->confirmed;
            $data['order_status'] = $o->order_status;
            $data['is_fee_retrieve'] = $o->is_fee_retrieve;
            $data['commission'] = $o->commission;
            $data['description'] = $o->description;
            $data['shipping_at'] = $o->shipping_at;
            $data['order_at'] = $o->order_at;
            $data['deleted_at'] = $o->deleted_at;
            $data['created_at'] = $o->created_at;
            $data['updated_at'] = $o->updated_at;
            $data['updated_at_format'] = $lastActivity;
            $orders[] = $data;
        }
        $content = [
            'status' => true,
            'message' => 'Lista de ordenes de venta.',
            'data' => $orders
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'client_id' => 'required',
                'payment_id' => 'required',
                'channel_id' => 'required',
                'total' => 'required',
                'discount' => 'required'
            ]);
            if ($validator->fails()) {
                $content = [
                    'status' => false,
                    'message' => 'Entradas no válidas.',
                    'error' => $validator->errors()
                ];
                return response()->json($content, 400);
            }
            $now = Carbon::now()->locale('es_MX');
            $description = !empty($request->description) ? $request->description : null;
            $shippingAt = !empty($request->shipping_at) ? $request->shipping_at : null;
            $salesOrderData = [
                'user_id' => $request->user_id,
                'client_id' => $request->client_id,
                'payment_type_id' => $request->payment_id,
                'channel_id' => $request->channel_id,
                'marketplace_id' => Str::random(100),
                'total' => $request->total,
                'confirmed' => True,
                'order_status' => 'Manual',
                'is_fee_retrieve' => False,
                'commission' => 0.0,
                'description' => $description,
                'shipping_at' => date("Y-m-d H:i:s", strtotime($shippingAt)),
                'order_at' => $now,
            ];
            $salesOrder = SaleOrder::create($salesOrderData);
            // Update "marketplace_id"
            $chanel = Channel::whereId($request->channel_id)->first();
            $marketplaceId = $salesOrder->id . '-' . $chanel->name . '-' . $now;
            $salesOrder->marketplace_id = $marketplaceId;
            $salesOrder->save();

            $discount = $request->discount / count($request->salesOrdeItems);
            foreach ($request->salesOrdeItems as $saleOrderItem) {
                $item = Item::whereId($saleOrderItem['item_id'])->first();
                $marketplaceItemId = 'Manual-' . $now . '-' . Str::random(4);
                $quantity = $saleOrderItem['item_quantity'];
                $price = $saleOrderItem['item_price'];
                $itemTotal = ($quantity * $price) - $discount;
                SaleOrderItem::create([
                    'sale_order_id' => $salesOrder->id,
                    'item_id' => $item->id,
                    'marketplace_item_id' => $marketplaceItemId,
                    'name' => $item->name,
                    'upc' => !empty($item->upc) ? $item->upc : 'sin-upc',
                    'sku' => $item->sku,
                    'quantity' => $quantity,
                    'cost' => $item->cost,
                    'price' => $price,
                    'tax' => 0.0,
                    'shipping_price' => 0.0,
                    'shipping_tax' => 0.0,
                    'shipping_discount' => 0.0,
                    'gift_wrap_price' => 0.0,
                    'gift_wrap_tax' => 0.0,
                    'gift_wrap_commission' => 0.0,
                    'discount' => $discount,
                    'commission' => 0.0,
                    'item_total' => $itemTotal,
                ]);
            }
            $content = [
                'status' => true,
                'message' => 'Orden de venta creada.'
            ];
            return response()->json($content, 200);
        } catch (\Exception $th) {
            $content = [
                'status' => false,
                'message' => 'Fallo algo.',
                'error' => $th->getMessage()
            ];
            return response()->json($content, 500);
        }

    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $order = SaleOrder::whereId($id)->first();
        if (is_null($order)) {
            $content = [
                'status' => false,
                'message' => 'Orden de venta no encontrada.'
            ];
            return response()->json($content, 404);
        }

        $saleOrder = SaleOrder::whereId($id)
            ->with('getUser')
            ->with('getClient')
            ->with('getPaymentType')
            ->with('getChannel')
            ->with('getSaleOrderItems')
            ->first();
        $content = [
            'status' => true,
            'message' => 'Orden de venta encontrada.',
            'data' => $saleOrder
        ];
        return response()->json($content, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $salesOrder = SaleOrder::whereId($id)->first();
        if (is_null($salesOrder)) {
            $content = [
                'status' => false,
                'message' => 'Orden de venta no encontrada.'
            ];
            return response()->json($content, 404);
        }
        $salesOrderData = [
            'confirmed' => false,
            'order_status' => 'deleted',
            'deleted_at' => Carbon::now()->locale('es_MX')
        ];
        $salesOrder->update($salesOrderData);
        $content = [
            'status' => true,
            'message' => 'Orden de venta eliminada.',
        ];
        return response()->json($content, 200);
    }

    public function export(): \Illuminate\Http\JsonResponse{
        $saleOrders = SaleOrder::with('getUser')
            ->with('getClient')
            ->with('getSaleOrderItems')
            ->with('getChannel')
            ->where('order_status', '!=', 'deleted')
            ->get();
        $orders = [];
        foreach ($saleOrders as $order){
            $items = [];
            foreach ($order->getSaleOrderItems as $item){
                $item = $item->name .','. $item->sku .', Cantidad: '. $item->quantity .' , Total: ' . $order->total;
                $items[]=$item;
            }
            $data['title'] = $order->getClient->name;
            $data['address'] =  $order->getClient->street_and_number .','. $order->getClient->interior_number .','. $order->getClient->colony .','. $order->getClient->municipality .','. $order->getClient->postcode .','. $order->getClient->between_streets .','. $order->getClient->reference;
            $data['items'] = implode($items);
            $data['order_id'] = $order->id;
            $data['client'] = $order->getClient->name;
            $data['phone'] = $order->getClient->phone;  
            $data['sms'] = $order->getClient->phone;            
            $data['total'] = $order->total;
            $data['sales_channel'] = $order->getChannel->name;
            $data['date'] = date("Y-m-d",strtotime($order['created_at']));
            $orders[] = $data;
        }
        $content = [
            'status' => true,
            'message' => 'Excel de ordenes de venta.',
            'data' => $orders
        ];
        return response()->json($content, 200);

    }
}
