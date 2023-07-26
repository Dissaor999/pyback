<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderItem extends Model
{
    protected $table = 'sale_order_items';
    protected $fillable = [
        'id',
        'sale_order_id',
        'item_id',
        'marketplace_item_id',
        'name',
        'upc',
        'sku',
        'quantity',
        'cost',
        'price',
        'tax',
        'shipping_price',
        'shipping_tax',
        'shipping_discount',
        'gift_wrap_price',
        'gift_wrap_tax',
        'gift_wrap_commission',
        'discount',
        'commission',
        'item_total',
        'created_at',
        'updated_at',
    ];

    public function getItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
}
