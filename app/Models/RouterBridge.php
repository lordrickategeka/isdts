<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterBridge extends Model
{
    protected $fillable = [
        'router_id',
        'name',
        'running',
        'attributes',
    ];

    protected $casts = [
        'running' => 'boolean',
        'attributes' => 'array',
    ];

    /* ======================
     | Relationships
     ====================== */

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function ports()
    {
        return $this->hasMany(RouterBridgePort::class);
    }
}
