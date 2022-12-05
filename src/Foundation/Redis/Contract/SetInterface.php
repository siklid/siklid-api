<?php

declare(strict_types=1);

namespace App\Foundation\Redis\Contract;

/**
 * @psalm-suppress MissingParamType
 */
interface SetInterface
{
    /**
     * Gets the number of members in a set.
     */
    public function size(string $key): int;

    /**
     * Adds the specified members to the set stored at key.
     */
    public function add(string $key, ...$members): int;

    /**
     * Returns if member is a member of the set stored at key.
     */
    public function contains(string $key, string $needle): bool;

    /**
     * Returns all the members of the set value stored at key.
     */
    public function members(string $key): array;

    /**
     * Removes the specified members from the set stored at key.
     */
    public function remove(string $sutKey, ...$members): int;
}
