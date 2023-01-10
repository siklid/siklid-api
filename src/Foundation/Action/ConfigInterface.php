<?php

declare(strict_types=1);

namespace App\Foundation\Action;

interface ConfigInterface
{
    /**
     * Returns all parameters.
     */
    public function all(): array;

    /**
     * Finds a parameter by name.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Returns true if the parameter exists.
     */
    public function has(string $key): bool;
}
