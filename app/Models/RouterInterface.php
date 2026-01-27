<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterInterface extends Model
{
    protected $fillable = [
        'router_id',
        'name',
        'type',
        'mac_address',
        'running',
        'disabled',
        'attributes',
    ];

    protected $casts = [
        'running' => 'boolean',
        'disabled' => 'boolean',
        'attributes' => 'array',
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function bridgePorts()
    {
        return $this->hasMany(RouterBridgePort::class);
    }

    public function vlans()
    {
        return $this->hasMany(RouterVlan::class, 'parent_interface_id');
    }

    public function ipAddresses()
    {
        return $this->hasMany(RouterIpAddress::class);
    }
}
