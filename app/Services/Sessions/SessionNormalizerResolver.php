<?php

namespace App\Services\Sessions;

use App\Services\Sessions\Normalizers\{
    HotspotActiveNormalizer,
    DhcpLeaseNormalizer,
    PppActiveNormalizer
};

class SessionNormalizerResolver
{
    public static function resolve(string $source)
    {
        return match ($source) {
            'hotspot_active' => new HotspotActiveNormalizer(),
            'dhcp_lease' => new DhcpLeaseNormalizer(),
            'ppp_active' => new PppActiveNormalizer(),
            default => null,
        };
    }
}
