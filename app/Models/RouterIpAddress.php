<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterIpAddress extends Model
{
    protected $fillable = [
        'router_id',
        'router_interface_id',
        'address',
        'network',
        'vrf',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    /* ======================
     | Relationships
     ====================== */

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function interface()
    {
        return $this->belongsTo(RouterInterface::class, 'router_interface_id');
    }
}
