<?php

namespace App\Services\Routers;

use App\Models\Router;
use App\Services\Routers\Clients\RouterOsApiClient;
use App\Contracts\RouterConnectionInterface;

class RouterConnectionFactory
{
    public static function make(Router $router): RouterConnectionInterface
    {
        return match ($router->connection_method) {
            'api' => new RouterOsApiClient($router),
            default => throw new \RuntimeException('Unsupported router connection method'),
        };
    }
}
