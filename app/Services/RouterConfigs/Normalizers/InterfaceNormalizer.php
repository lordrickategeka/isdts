<?php

namespace App\Services\RouterConfigs\Normalizers;

class InterfaceNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(fn ($r) => [
            'router_id' => $routerId,
            'name' => $r['name'],
            'type' => $r['type'] ?? null,
            'mac_address' => $r['mac-address'] ?? null,
            'running' => ($r['running'] ?? 'false') === 'true',
            'disabled' => ($r['disabled'] ?? 'false') === 'true',
            'attributes' => $r,
        ])->all();
    }
}
