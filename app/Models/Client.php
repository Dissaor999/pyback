<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = [
        'id',
        'name',
        'rfc',
        'phone',
        'email',
        'status',
        'street_and_number',
        'interior_number',
        'colony',
        'municipality',
        'postcode',
        'between_streets',
        'reference',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];
}
