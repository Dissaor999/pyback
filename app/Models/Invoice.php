<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'id',
        'sale_order_id',
        'user_id',
        'client_id',
        'payment_type_id',
        'channel_id',
        'payment_method',
        'uuid',
        'description',
        'total',
        'invoice_status',
        'url_xml',
        'url_pdf',
        'created_at',
        'updated_at',
    ];

    //entity relationship BelongsTo
    public function getSaleOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\SaleOrder', 'sale_order_id', 'id');
    }

    public function getUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getClient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }

    public function getPaymentType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\PaymentType', 'payment_type_id', 'id');
    }

    public function getChannel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Channel', 'channel_id', 'id');
    }
}
