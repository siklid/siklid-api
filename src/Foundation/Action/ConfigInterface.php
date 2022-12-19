<?php

declare(strict_types=1);

namespace App\Foundation\Action;

use UnitEnum;

interface ConfigInterface
{
    /**
     * Returns all parameters.
     */
    public function all(): array;

    /**
     * Finds a parameter by name.
     */
    public function get(
        string $key,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null;

    /**
     * Returns true if the parameter exists.
     */
    public function has(string $key): bool;
}
