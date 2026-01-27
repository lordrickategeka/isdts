<?php

namespace App\Services\RouterConfigs\Executors;

class BridgeExecutor
{
    public function pull($conn): array
    {
        return $conn->execute('/interface/bridge/print');
    }
}
