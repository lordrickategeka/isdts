<?php

namespace App\Services\Routers;

use App\Services\Routers\Executors\{
    HotspotActiveExecutor,
    DhcpLeaseExecutor,
    PppActiveExecutor
};

class TruthSourceResolver
{
    public static function resolve(string $source)
    {
        return match ($source) {
            'hotspot_active' => new HotspotActiveExecutor(),
            'dhcp_lease'     => new DhcpLeaseExecutor(),
            'ppp_active'     => new PppActiveExecutor(),
            default => throw new \RuntimeException("Unknown truth source: {$source}")
        };
    }
}
