<?php

namespace App\Services\Sessions\Normalizers;

use App\Services\Sessions\Dto\ObservedSession;

class HotspotActiveNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(function ($row) use ($routerId) {
            $s = new ObservedSession();

            $s->routerId = $routerId;
            $s->mac = $row['mac-address'] ?? null;
            $s->ip = $row['address'] ?? null;
            $s->username = $row['user'] ?? null;
            $s->accessType = 'hotspot';
            $s->authenticated = true;
            $s->bytesIn = $row['bytes-in'] ?? null;
            $s->bytesOut = $row['bytes-out'] ?? null;

            $s->attributes = [
                'interface' => $row['interface'] ?? null,
                'uptime' => $row['uptime'] ?? null,
                'interface' => $row['interface'] ?? null,
                'uptime' => $row['uptime'] ?? null,
                'sessionTimeLeft' => $row['session-time-left'] ?? null,
                'idleTime' => $row['idle-time'] ?? null,
            ];

            return $s;
        })->all();
    }
}
