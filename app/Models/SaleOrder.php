<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'sale_orders';
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'payment_type_id',
        'channel_id',
        'marketplace_id',
        'total',
        'confirmed',
        'order_status',
        'is_fee_retrieve',
        'commission',
        'description',
        'invoice_status',
        'shipping_at',
        'order_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $guarded = [];

    //entity relationship BelongsTo
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

    // entity relationship HasMany
    public function getSaleOrderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\SaleOrderItem', 'sale_order_id', 'id');
    }

}
