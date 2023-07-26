<?php

namespace App\Http\Controllers\join\listo;

use App\Http\Controllers\Controller;
use App\Models\Key;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Item;

//liobraries
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->id_listo = '36283';
        $this->baseurl1 = 'https://staging.listo.mx/api/';
        $this->baseurl = 'https://listo.mx/api/';
        $this->token = Key::where('key', 'token_listo')->first()->value;

    }

    function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    public function listoGetXml($data)
    {
        //Traemos los Datos de la orden
        $order = SaleOrder::whereId($data->order_id)
            ->with('getSaleOrderItems')
            ->with('getClient')
            ->first();
        $customer = $order->getClient;
        //Lista de Items
        $itemsList = [];
        foreach ($order->getSaleOrderItems as $item) {
            $ItemData = Item::where('id',$item->item_id)->first();
            $discount = 0;
            $shipp = 0;
            if ($item->discount > 0 || $item->shipping_discount > 0) {
                $discount = $item->shipping_discount + $item->discount;
            }
            if ($item->shipping_price > 0 || $item->shipping_tax > 0) {
                $shipp = $item->shipping_price + $item->shipping_tax;
            }
            $eval = ($shipp - $discount);
            if ($eval == 0) {
                $discount = 0;
                $shipp = 0;
            } else {
                $shipp = $shipp / $item->quantity;
            }
            $unitprice = (($item->price + $shipp) / 1.16);
            $itemarr = [
                "id" => (string)$ItemData->sku . "/" . $ItemData->upc,
                "discount" => (string)$discount,
                "description" => $this->cleanString($item->name),
                "quantity" => $item->quantity,
                "unitary_amount" => (string)$unitprice,
                "product_code" => $ItemData->sat_code,
                "units_code" => "H87",
                "units" => "una",
                "taxes" => [
                    "pass_through" => [
                        [
                            "base" => (string)number_format(($unitprice * $item->quantity), 2, '.', ''),
                            "amount" => (string)number_format(($unitprice * $item->quantity) * 0.16, 2, '.', ''),
                            "rate" => "16.00000",
                            "tax" => "IVA"
                        ],
                    ],
                ],
                "taxation_type" => "02",
            ];

            $itemsList[] = $itemarr;
        }
        //Modelo de La factura
        $invoice = new Invoice();
        $currentDateTime = Carbon::now();
        $factura = [
            "series" => "A",
            "folio" => (string)$invoice->id,
            "issued_on" => (string)$currentDateTime->format('Y-m-d\TH:i:s'),
            "issued_at" => $customer->postcode,
            "payment_form" => $data->paymet_method,
            "payment_method" => $data->payment_way,
            "comments" => "",
            "currency" => "MXN",
            "exchange_rate" => "1",
            "version" => "4.0",
            "reimbursement" => false,
            "issuer" => [
                "id" => $this->id_listo,
                "rfc" => "MOPG981130MR4",
                "rfc_name" => 'GIANNI MOLINARI PARAMO',
                "tax_regime" => "626"
            ],
            "receiver" => [
                "rfc" => $customer->rfc,
                "rfc_name" => $customer->name,
                "intended_use" => $data->cdfi_use,
                "tax_regime" => "626", //(string)$customer->type,
                "address" => [
                    "postal_code" => $customer->postcode,
                ]
            ],
            "items" => $itemsList,
            "taxes" => [],
            "export_type" => "01",
            "related_cfdis_type" => "",
            "related_cfdis" => []
        ];
        $props = [
            'headers' => [
                'Authorization' => 'Token ' . $this->token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([$factura]),
        ];
        //dd($props);
        try {
            $url = $this->baseurl . 'invoicing/generate_xml';
            $client = new Client();
            $responseJson = $client->request('POST', $url, $props)->getBody()->getContents();
            $response = json_decode($responseJson);

            //return $response;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsJson = json_decode($response->getBody()->getContents());
            $ret = [
                'status' => 'error',
                'message' => $responseBodyAsJson
            ];
            return $ret;
        }

        try {
            $certificar = $this->xmlCert($response[0]->xml, $factura, $response[0]->original_chain);
            if (!isset($certificar->invoice->pdf->filename)) {
                throw new \Exception($certificar->error_description);
            }
            $pdfile = explode(".", $certificar->invoice->pdf->filename);
            $uuid = $pdfile['0'];
            $invoice->uuid = $uuid;
            $invoice->total = $certificar->invoice->total;
            $invoice->url_xml = $certificar->invoice->xml->url;
            $invoice->url_pdf = $certificar->invoice->pdf->url;
            $invoice->sale_order_id = $order->id;
            $invoice->user_id = 5;
            $invoice->client_id = $customer->id;
            $invoice->payment_type_id = 2;
            $invoice->channel_id = $order->channel_id;
            $invoice->payment_method = $data->payment_way;
            $invoice->save();
            $ret = [
                'status' => 'success',
                'message' => 'Factura Generada'
            ];
            return $ret;
        } catch (\Exception $th) {

            $ret = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return $ret;
        }

    }

    public function xmlCert($xml, $data, $cadenao)
    {
        $certxml = simplexml_load_string($xml);
        $cer = file_get_contents('./uploads/00001000000514218230.cer', true);
        $cert = base64_encode($cer);
        $certxml['Certificado'] = $cert;
        $certxml['NoCertificado'] = "00001000000514218230";

        $cadena = str_replace("00000000000000000000", "00001000000514218230", $cadenao);

        $key = file_get_contents('./uploads/CSD_UNIDAD_MOPG981130MR4_20220728_162456.key.pem', true);
        openssl_sign($cadena, $digest, $key, OPENSSL_ALGO_SHA256);
        $sello = base64_encode($digest);
        $dataCert = [
            "data" => $data,
            "xml" => $xml,
            "signature" => $sello,
            "permanent_urls" => True,
            "certificate_num" => "00001000000514218230",
            "certificate" => $cert
        ];
        $props = [
            'headers' => [
                'Authorization' => 'Token ' . $this->token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($dataCert),
        ];

        try {
            $url = $this->baseurl . 'invoicing/certify_xml';
            $client = new Client();
            $responseJson = $client->request('POST', $url, $props)
                ->getBody()->getContents();
            $response = json_decode($responseJson);
            return $response;
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $responseBodyAsJson = json_decode($response->getBody()->getContents());
            $ret = [
                'status' => 'error',
                'message' => $responseBodyAsJson->message
            ];
            return $ret;
        }
    }
}
