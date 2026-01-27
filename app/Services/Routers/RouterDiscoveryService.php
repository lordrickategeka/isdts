<?php

namespace App\Services\Routers;

use App\Models\Router;

class RouterDiscoveryService
{
    public function discover(Router $router): array
    {
        $conn = RouterConnectionFactory::make($router);

        if (! $conn->connect()) {
            return [
                'success' => false,
                'error' => $conn->getLastError(),
            ];
        }

        if (! $conn->connect()) {
            logger()->error('Router API connect failed', [
                'router_id' => $router->id,
                'ip' => $router->management_ip,
                'error' => $conn->getLastError(),
            ]);

            return [
                'success' => false,
                'error' => $conn->getLastError(),
            ];
        }
        
        // 1. Identity
        $identity = $conn->execute('/system/identity/print')[0]['name'] ?? null;

        // 2. Resource
        $resource = $conn->execute('/system/resource/print')[0] ?? [];

        // 3. Capability inference
        $capabilities = [
            'hotspot' => true, // assume yes, confirm later
            'pppoe' => true,
            'dhcp' => true,
            'firewall' => true,
            'api_ssl' => $router->use_tls,
        ];

        $router->update([
            'identity' => $identity,
            'os_version' => $resource['version'] ?? null,
            'board_name' => $resource['board-name'] ?? null,
            'capabilities' => $capabilities,
            'is_active' => true,
            'last_seen_at' => now(),
            'poll_failures' => 0,
        ]);

        $conn->disconnect();

        return [
            'success' => true,
            'identity' => $identity,
            'os_version' => $router->os_version,
        ];
    }
}
