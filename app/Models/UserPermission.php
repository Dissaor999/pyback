<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'user_permissions';
    protected $fillable = [
        'id',
        'user_id',
        'permission_id',
        'created_at',
        'updated_at',
    ];

    //entity relationship BelongsTo
    public function getPermission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Permission', 'permission_id', 'id');
    }
}
