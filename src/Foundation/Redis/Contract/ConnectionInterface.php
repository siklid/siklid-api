<?php

declare(strict_types=1);

namespace App\Foundation\Redis\Contract;

/**
 * Redis connection interface.
 */
interface ConnectionInterface
{
    /**
     * Connects to the Redis instance.
     */
    public function connect(
        string $host,
        int $port = 6379,
        float $timeout = 0.0,
        mixed $reserved = null,
        int $retryInterval = 0,
        float $readTimeout = 0.0
    ): void;

    /**
     * Pings the Redis instance.
     */
    public function ping(): string;

    /**
     * Closes the connection to the Redis instance.
     */
    public function close(): bool;

    /**
     * Sends a command to the Redis instance.
     */
    public function command(string $command, array $args = []): mixed;
}
