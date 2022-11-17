<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use BackedEnum;

/**
 * All enums used for `specific` type must implement this interface.
 */
interface CoercibleEnum extends BackedEnum
{
    /**
     * Coerce value to enum.
     */
    public static function coerce(mixed $value): self;

    /**
     * Converts the enum to an array.
     *
     * @return array{name: class-string<self>, value: string}
     */
    public function toArray(): array;
}
