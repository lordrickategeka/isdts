<?php

namespace App\Services\RouterConfigs\Executors;

class IpAddressExecutor
{
    public function pull($conn): array
    {
        return $conn->execute('/ip/address/print');
    }
}
