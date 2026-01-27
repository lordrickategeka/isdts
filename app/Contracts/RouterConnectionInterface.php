<?php

namespace App\Contracts;

interface RouterConnectionInterface
{
   public function connect(): bool;

    public function execute(string $command, array $params = []): array;

    public function disconnect(): void;

    public function getLastError(): ?string;

    public function getLatencyMs(): ?int;
}
