<?php

namespace App\Services\RouterConfigs;

use App\Models\Router;
use App\Services\Routers\RouterConnectionFactory;
use App\Services\RouterConfigs\Resolvers\ConfigExecutorResolver;
use App\Services\RouterConfigs\Resolvers\ConfigNormalizerResolver;
use App\Services\RouterConfigs\Resolvers\ConfigWriterResolver;

class RouterConfigSyncService
{
    public function sync(Router $router, array $targets = []): void
    {
        $conn = RouterConnectionFactory::make($router);

        if (! $conn->connect()) {
            logger()->error('Config sync failed: cannot connect', [
                'router_id' => $router->id,
                'error' => $conn->getLastError(),
            ]);
            return;
        }

        $targets = $targets ?: [
            'interfaces',
            'bridges',
            'bridge_ports',
            'vlans',
            'ip_addresses',
        ];

        foreach ($targets as $target) {
            $executor   = ConfigExecutorResolver::resolve($target);
            $normalizer = ConfigNormalizerResolver::resolve($target);
            $writer     = ConfigWriterResolver::resolve($target);

            if (! $executor || ! $normalizer || ! $writer) {
                continue;
            }

            $raw = $executor->pull($conn);
            $normalized = $normalizer->normalize($raw, $router->id);

            $writer->reconcile($router->id, $normalized);
        }

        $conn->disconnect();
    }
}
