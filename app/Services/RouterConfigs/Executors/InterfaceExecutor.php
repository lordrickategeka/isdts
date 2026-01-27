<?php
namespace App\Services\RouterConfigs\Executors;

class InterfaceExecutor
{
    public function pull($conn): array
    {
        return $conn->execute('/interface/print');
    }
}
