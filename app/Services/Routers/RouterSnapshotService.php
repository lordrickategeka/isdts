<?php

namespace App\Services\Routers;

use App\Models\Router;
use App\Models\RouterSnapshot;

use App\Services\Sessions\SessionCorrelationEngine;

class RouterSnapshotService
{
    public function capture(
        Router $router,
        string $source,
        SessionCorrelationEngine $correlator
    ): void {
        $connection = RouterConnectionFactory::make($router);

        if (! $connection->connect()) {
            $snapshot = RouterSnapshot::create([
                'router_id'   => $router->id,
                'source'      => $source,
                'payload'     => [],
                'captured_at' => now(),
                'success'     => false,
                'error_message' => $connection->getLastError(),
            ]);
            return;
        }

        $executor = TruthSourceResolver::resolve($source);
        $data = $executor->pull($connection);

        $snapshot = RouterSnapshot::create([
            'router_id'    => $router->id,
            'source'       => $source,
            'payload'      => $data,
            'record_count' => count($data),
            'captured_at'  => now(),
            'duration_ms'  => $connection->getLatencyMs(),
            'success'      => true,
        ]);

        $connection->disconnect();

        // 🔥 THIS IS THE WIRING
        $correlator->processSnapshot($snapshot);
    }
}
