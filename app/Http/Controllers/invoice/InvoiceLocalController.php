<?php

namespace App\Http\Controllers\invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SaleOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
//Controlador de Listo
use App\Http\Controllers\join\listo\InvoiceController;


class InvoiceLocalController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $saleOrders = SaleOrder::where('confirmed', 1)
            ->where('order_status', '!=', 'deleted')
            ->where('invoice_status', 0)
            ->with('getUser')
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
            'message' => 'Lista de ordenes por facturar.',
            'data' => $orders
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'paymet_method' => 'required',
            'payment_way' => 'required',
            'cdfi_use' => 'required'
        ]);
        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas.',
                'error' => $validator->errors()
            ];
            return response()->json($content, 400);
        }
        # TODO falta rutina de creacion por API
        try {
            $invoice = new InvoiceController();
            $message = $invoice->listoGetXml($request);

        }catch (\Exception $e){

                $message = [
                    'status'    => 'error',
                    'message'   => (string)$e
                ];
        }
        return response()->json($message, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $invoice = Invoice::whereId($id)->first();
        if (is_null($invoice)) {
            $content = [
                'status' => false,
                'message' => 'Factura no encontrada.'
            ];
            return response()->json($content, 404);
        }
        $invoice = Invoice::whereId($id)
            ->with('getSaleOrder.getSaleOrderItems.getItem')
            ->with('getUser')
            ->with('getClient')
            ->with('getPaymentType')
            ->with('getChannel')
            ->first();
        $content = [
            'success' => true,
            'message' => 'Factura encontrada.',
            'data' => $invoice
        ];
        return response()->json($content, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $invoice = Invoice::whereId($id)->first();
        if (is_null($invoice)) {
            $content = [
                'status' => false,
                'message' => 'Factura no encontrada.'
            ];
            return response()->json($content, 404);
        }
        # TODO falta rutina de cancelacion API

        $content = [
            'status' => true,
            'message' => 'Factura cancelada exitosamente.'
        ];
        return response()->json($content, 200);
    }

    public function invoiced(): \Illuminate\Http\JsonResponse
    {
        $invoices = Invoice::where('invoice_status', 1)
            ->with('getSaleOrder.getSaleOrderItems.getItem')
            ->with('getUser')
            ->with('getClient')
            ->with('getPaymentType')
            ->with('getChannel')
            ->get();

        $invoiced = [];
        $date = Carbon::now();
        foreach ($invoices as $o) {
            $lastActivity = $date->createFromTimeStamp(strtotime($o['updated_at']))->diffForHumans();
            $data['id'] = $o->id;
            $data['user_id'] = $o->getUser->name;
            $data['client_id'] = $o->getClient->name;
            $data['payment_type_id'] = $o->getPaymentType->name;
            $data['uuid'] = $o->uuid;
            $data['total'] = $o->total;
            $data['invoice_status'] = $o->invoice_status;
            $data['url_xml'] = $o->url_xml;
            $data['url_pdf'] = $o->url_pdf;
            $data['created_at'] = $o->created_at;
            $data['updated_at_format'] = $lastActivity;
            $invoiced[] = $data;
        }

        $content = [
            'success' => true,
            'message' => 'Facturas encontradas.',
            'data' => $invoiced
        ];
        return response()->json($content, 200);
    }
}
