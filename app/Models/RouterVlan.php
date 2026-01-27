<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterVlan extends Model
{
    protected $fillable = [
        'router_id',
        'name',
        'vlan_id',
        'parent_interface_id',
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

    public function parentInterface()
    {
        return $this->belongsTo(RouterInterface::class, 'parent_interface_id');
    }
}
