<?php

namespace App\Services\Sessions\Dto;

class ObservedSession
{
    public string $routerId;
    public ?string $mac = null;
    public ?string $ip = null;
    public ?string $username = null;
    public string $accessType;
    public bool $authenticated = false;
    public ?int $bytesIn = null;
    public ?int $bytesOut = null;
    public array $attributes = [];
    public ?string $interfaceName = null;

    public function confidenceScore(): int
    {
        $score = 0;

        if ($this->mac) $score += 40;
        if ($this->ip) $score += 20;
        if ($this->username) $score += 30;
        if ($this->authenticated) $score += 10;

        return min($score, 100);
    }
}
