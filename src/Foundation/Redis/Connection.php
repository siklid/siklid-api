<?php

declare(strict_types=1);

namespace App\Foundation\Redis;

use App\Foundation\Redis\Contract\ConnectionInterface;
use Redis;
use RedisException;

/**
 * @psalm-suppress UndefinedClass - Redis class is defined in the extension
 */
class Connection implements ConnectionInterface
{
    private Redis $redis;

    /**
     * @psalm-suppress UndefinedClass - Redis class is defined in the extension
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @psalm-suppress MixedArgument - $reserved argument is known to be mixed
     *
     * @throws RedisException
     */
    public function connect(
        string $host,
        int $port = 6379,
        float $timeout = 0.0,
        mixed $reserved = null,
        int $retryInterval = 0,
        float $readTimeout = 0.0
    ): void {
        if (0 !== $retryInterval) {
            assert(null === $reserved);
        }

        $this->redis->connect($host, $port, $timeout, $reserved, $retryInterval, $readTimeout);
    }

    /**
     * @throws RedisException
     */
    public function ping(): string
    {
        /** @var true|string $result */
        $result = $this->redis->ping();

        return true === $result ? 'PONG' : $result;
    }

    /**
     * @throws RedisException
     *
     * @psalm-suppress MixedInferredReturnType - We know that the result is a boolean
     * @psalm-suppress MixedReturnStatement - We know that the result is a boolean
     */
    public function close(): bool
    {
        return $this->redis->close();
    }
}
