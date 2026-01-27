<?php

namespace App\Services\RouterConfigs\Normalizers;

class BridgeNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(fn ($r) => [
            'router_id' => $routerId,
            'name' => $r['name'],
            'running' => ($r['running'] ?? 'false') === 'true',
            'attributes' => $r,
        ])->all();
    }
}
