<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterBridgePort extends Model
{
    protected $fillable = [
        'router_bridge_id',
        'router_interface_id',
        'disabled',
        'attributes',
    ];

    protected $casts = [
        'disabled' => 'boolean',
        'attributes' => 'array',
    ];

    /* ======================
     | Relationships
     ====================== */

    public function bridge()
    {
        return $this->belongsTo(RouterBridge::class, 'router_bridge_id');
    }

    public function interface()
    {
        return $this->belongsTo(RouterInterface::class, 'router_interface_id');
    }
}
