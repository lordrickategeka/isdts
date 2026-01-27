<?php

namespace App\Services\Routers\Executors;

use App\Contracts\RouterConnectionInterface;

class PppActiveExecutor
{
    public function pull(RouterConnectionInterface $conn): array
    {
        return $conn->execute('/ppp/active/print');
    }
}
