<?php

declare(strict_types=1);

namespace App\Foundation\Redis;

use App\Foundation\Redis\Contract\SetInterface;
use Redis;
use RedisException;

/**
 * @psalm-suppress MissingParamType - Allows usage of splat operator EX. (...$members)
 * @psalm-suppress MixedArgument - Allows usage of splat operator EX. (...$members)
 */
class Set implements SetInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @throws RedisException
     */
    public function size(string $key): int
    {
        $size = $this->redis->scard($key);
        assert(! $size instanceof Redis);

        return (int)$size;
    }

    public function add(string $key, ...$members): int
    {
        foreach ($members as $member) {
            assert(is_string($member));
        }

        $count = $this->redis->sadd($key, ...$members);
        assert(! $count instanceof Redis);

        return (int)$count;
    }

    /**
     * @throws RedisException
     */
    public function contains(string $key, string $needle): bool
    {
        $contains = $this->redis->sismember($key, $needle);
        assert(! $contains instanceof Redis);

        return $contains;
    }

    /**
     * @throws RedisException
     */
    public function members(string $key): array
    {
        $members = $this->redis->smembers($key);
        assert(! $members instanceof Redis);

        return $members;
    }

    public function remove(string $sutKey, ...$members): int
    {
        foreach ($members as $member) {
            assert(is_string($member));
        }

        $count = $this->redis->srem($sutKey, ...$members);
        assert(! $count instanceof Redis);

        return (int)$count;
    }

    /**
     * @throws RedisException
     */
    public function getTtl(string $key): int
    {
        $ttl = $this->redis->ttl($key);
        assert(! $ttl instanceof Redis);

        return (int)$ttl;
    }

    /**
     * @throws RedisException
     */
    public function setTtl(string $key, int $ttl): bool
    {
        $response = $this->redis->expire($key, $ttl);
        assert(! $response instanceof Redis);

        return $response;
    }
}
