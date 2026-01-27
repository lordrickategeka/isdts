<?php

namespace App\Services\Sessions\Normalizers;

use App\Services\Sessions\Dto\ObservedSession;

class DhcpLeaseNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)
            ->where('status', 'bound')
            ->map(function ($row) use ($routerId) {
                $s = new ObservedSession();

                $s->routerId = $routerId;
                $s->mac = $row['mac-address'] ?? null;
                $s->ip = $row['address'] ?? null;
                $s->accessType = 'dhcp_only';
                $s->authenticated = false;

                $s->attributes = [
                    'hostname' => $row['host-name'] ?? null,
                ];

                return $s;
            })->all();
    }
}
