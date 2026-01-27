<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Router extends Model
{
    protected $table = 'router_profiles';

    protected $fillable = [
        'tenant_id',
        'name',
        'site',
        'ownership',
        'identity',
        'serial_number',
        'board_name',
        'management_ip',
        'api_port',
        'connection_method',
        'use_tls',
        'timeout_seconds',
        'username',
        'password',
        'credential_ref',
        'rotate_credentials',
        'credentials_rotated_at',
        'role',
        'uplink_type',
        'capabilities',
        'is_active',
        'last_seen_at',
        'last_polled_at',
        'poll_failures',
        'disabled_at',
        'os_type',
        'os_version',
        'metadata',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'metadata' => 'array',
        'use_tls' => 'boolean',
        'is_active' => 'boolean',
        'rotate_credentials' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_polled_at' => 'datetime',
        'credentials_rotated_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    // Relationships
    public function truthSources(): HasMany
    {
        return $this->hasMany(RouterTruthSource::class);
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(RouterSnapshot::class);
    }

    public function networkSessions(): HasMany
    {
        return $this->hasMany(NetworkSession::class);
    }

    public function enforcements(): HasMany
    {
        return $this->hasMany(NetworkEnforcement::class);
    }

    // Accessors
    public function getStatusAttribute(): string
    {
        if (!$this->is_active || $this->disabled_at) {
            return 'offline';
        }

        if (!$this->last_seen_at) {
            return 'unknown';
        }

        $minutesSinceLastSeen = Carbon::now()->diffInMinutes($this->last_seen_at);

        if ($minutesSinceLastSeen <= 5) {
            return 'online';
        }

        if ($minutesSinceLastSeen <= 15) {
            return 'degraded';
        }

        return 'offline';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'online' => 'green',
            'degraded' => 'yellow',
            'offline' => 'red',
            default => 'gray',
        };
    }

    public function getCapabilityListAttribute(): array
    {
        $capabilities = $this->capabilities ?? [];
        $active = [];

        foreach ($capabilities as $key => $value) {
            if ($value === true) {
                $active[] = ucfirst($key);
            }
        }

        return $active;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereNull('disabled_at');
    }

    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', Carbon::now()->subMinutes(5));
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    //configured relationships

    public function interfaces()
    {
        return $this->hasMany(RouterInterface::class);
    }

    public function bridges()
    {
        return $this->hasMany(RouterBridge::class);
    }

    public function vlans()
    {
        return $this->hasMany(RouterVlan::class);
    }

    public function ipAddresses()
    {
        return $this->hasMany(RouterIpAddress::class);
    }
}
