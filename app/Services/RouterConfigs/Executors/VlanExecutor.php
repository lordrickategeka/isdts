<?php

namespace App\Services\RouterConfigs\Executors;

class VlanExecutor
{
    public function pull($conn): array
    {
        return $conn->execute('/interface/vlan/print');
    }
}
