<?php

declare(strict_types=1);

namespace App\Foundation\Redis;

use App\Foundation\Redis\Contract\ConnectionInterface;
use App\Foundation\Redis\Contract\SetInterface;

/**
 * @psalm-suppress MissingParamType
 */
class Set implements SetInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function size(string $key): int
    {
        return (int)$this->connection->command('SCARD', [$key]);
    }

    public function add(string $key, ...$members): int
    {
        foreach ($members as $member) {
            assert(is_string($member));
        }

        return (int)$this->connection->command('SADD', array_merge([$key], $members));
    }

    public function contains(string $key, string $needle): bool
    {
        return (bool)$this->connection->command('SISMEMBER', [$key, $needle]);
    }

    public function members(string $key): array
    {
        return (array)$this->connection->command('SMEMBERS', [$key]);
    }

    public function remove(string $sutKey, ...$members): int
    {
        foreach ($members as $member) {
            assert(is_string($member));
        }

        return (int)$this->connection->command('SREM', array_merge([$sutKey], $members));
    }
}
