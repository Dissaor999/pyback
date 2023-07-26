<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemMatchController extends Controller
{
    public function itemMatch($upc, $sku): array
    {
        $upc = !empty($upc) ? $upc : null;
        $sku = !empty($sku) ? $sku : null;
        $im = Item::where('upc', $upc)->first();
        if (!empty($upc) && !empty($im->id)) {
            null;
            // dd(['test 1', 'sku' => $sku, 'upc' => $upc, 'id' => $im]);
        } else {
            if (!empty($upc) && strlen($upc) >= 12) {
                $im = Item::where('upc', 'like', '%' . $upc . '%')->first();
                // dd(['test 2', 'sku' => $sku, 'upc' => $upc , 'id' => $im]);
            }
            if (empty($im->id)) {
                $im = Item::where('sku', $sku)->first();
                // dd(['test 3', 'sku' => $sku, 'upc' => $upc, 'id' => $im]);
                if (empty($im->id)) {
                    $subSku = substr($sku, 0, 5);
                    $im = Item::where('sku', (string)$subSku)->first();
                    // dd(['test 4', 'sku' => $sku, 'upc' => $upc, 'id' => $im]);
                    if (empty($im->id) && !empty($sku) && strlen($sku) >= 4) {
                        $im = Item::where('sku', 'like', (string)$subSku . '%')->first();
                        //  dd(['test 5','sku' => $sku,'Upc' => $upc,  'id' => $im]);
                    }
                }
            }
        }
        $itemNoMatch = Item::whereId(1)->first();
        $im = [
            'id' => !empty($im->id) ? $im->id : $itemNoMatch->id,
            'zoho_id' => !empty($im->id) ? $im->zoho_id : $itemNoMatch->zoho_id,
            'name' => !empty($im->id) ? $im->name : $itemNoMatch->name,
            'upc' => !empty($im->id) ? $im->upc : $upc,
            'sku' => !empty($im->id) ? $im->sku : $sku,
            'sat_code' => !empty($im->id) ? $im->sat_code : $itemNoMatch->sat_code,
            'product_type' => !empty($im->id) ? $im->product_type : $itemNoMatch->product_type,
            'status' => !empty($im->id) ? $im->status : $itemNoMatch->status,
            'total_stock' => !empty($im->id) ? $im->total_stock : $itemNoMatch->total_stock,
            'commission' => !empty($im->id) ? $im->commission : $itemNoMatch->commission,
            'cost' => !empty($im->id) ? $im->cost : $itemNoMatch->cost,
            'price' => !empty($im->id) ? $im->price : $itemNoMatch->price,
            'created_at' => !empty($im->id) ? $im->created_at : $itemNoMatch->created_at,
            'updated_at' => !empty($im->id) ? $im->updated_at : $itemNoMatch->updated_at,
        ];
        return $im;
    }
}
