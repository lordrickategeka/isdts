<?php

namespace App\Services\RouterConfigs\Executors;

class BridgePortExecutor
{
    public function pull($conn): array
    {
        return $conn->execute('/interface/bridge/port/print');
    }
}
