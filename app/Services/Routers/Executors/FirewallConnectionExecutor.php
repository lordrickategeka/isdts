<?php

namespace App\Services\Routers\Executors;

use App\Contracts\RouterConnectionInterface;

class FirewallConnectionExecutor
{
    public function pull(RouterConnectionInterface $conn): array
    {
        return $conn->execute('/ip/firewall/connection/print');
    }
}
