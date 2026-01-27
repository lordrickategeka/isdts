<?php

namespace App\Services\Sessions\Normalizers;

use App\Services\Sessions\Dto\ObservedSession;

class PppActiveNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(function ($row) use ($routerId) {
            $s = new ObservedSession();

            $s->routerId = $routerId;
            $s->username = $row['name'] ?? null;
            $s->ip = $row['address'] ?? null;
            $s->accessType = 'ppp';
            $s->authenticated = true;

            $s->attributes = [
                'caller_id' => $row['caller-id'] ?? null,
                'service' => $row['service'] ?? null,
            ];

            return $s;
        })->all();
    }
}
