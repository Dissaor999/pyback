<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $table = 'payment_types';
    protected $fillable = [
        'id',
        'name',
        'sat_code',
        'created_at',
        'updated_at',
    ];
}
