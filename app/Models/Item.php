<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = [
        'id',
        'zoho_id',
        'name',
        'upc',
        'sku',
        'sat_code',
        'product_type',
        'status',
        'total_stock',
        'commission',
        'cost',
        'price',
        'created_at',
        'updated_at',
    ];
}
