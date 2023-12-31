<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];
}
