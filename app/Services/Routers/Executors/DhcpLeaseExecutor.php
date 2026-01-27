<?php

namespace App\Services\Routers\Executors;

use App\Contracts\RouterConnectionInterface;

class DhcpLeaseExecutor
{
    public function pull(RouterConnectionInterface $conn): array
    {
        return $conn->execute('/ip/dhcp-server/lease/print');
    }
}
