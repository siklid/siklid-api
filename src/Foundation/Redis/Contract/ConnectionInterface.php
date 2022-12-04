<?php

declare(strict_types=1);

namespace App\Foundation\Redis\Contract;

interface ConnectionInterface
{
    public function connect(
        string $host,
        int $port = 6379,
        float $timeout = 0.0,
        mixed $reserved = null,
        int $retryInterval = 0,
        float $readTimeout = 0.0
    ): void;

    public function ping(): string;

    public function close(): bool;
}
