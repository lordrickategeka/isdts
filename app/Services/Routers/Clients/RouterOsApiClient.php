<?php

namespace App\Services\Routers\Clients;

use App\Models\Router;
use App\Contracts\RouterConnectionInterface;
use RouterOS\Client;
use RouterOS\Exceptions\ClientException;

class RouterOsApiClient implements RouterConnectionInterface
{
    protected Router $router;
    protected ?Client $client = null;
    protected ?string $lastError = null;
    protected ?int $latencyMs = null;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function connect(): bool
    {
        $start = microtime(true);

        try {
            $this->client = new Client([
                'host'    => $this->router->management_ip,
                'user'    => $this->router->username,
                'pass'    => decrypt($this->router->password),
                'port'    => $this->router->api_port,
                'timeout' => $this->router->timeout_seconds,
                'ssl'     => $this->router->use_tls,
            ]);

            $this->latencyMs = (int)((microtime(true) - $start) * 1000);
            return true;

        } catch (ClientException|\Throwable $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function execute(string $command, array $params = []): array
    {
        return $this->client
            ->query($command)
            ->read();
    }

    public function disconnect(): void
    {
        $this->client = null;
    }

    public function isConnected(): bool
    {
        return $this->client !== null;
    }

    public function sendCommand(string $command)
    {
        return $this->execute($command);
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function getLatencyMs(): ?int
    {
        return $this->latencyMs;
    }
}
