<?php

namespace App\Services\Routers\Executors;

use App\Contracts\RouterConnectionInterface;

class HotspotActiveExecutor
{
    public function pull(RouterConnectionInterface $conn): array
    {
        return $conn->execute('/ip/hotspot/active/print');
    }
}
