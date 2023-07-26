<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $table = 'keys';
    protected $fillable = [
        'id',
        'channel',
        'name',
        'key',
        'value',
        'created_at',
        'updated_at',
    ];
}
