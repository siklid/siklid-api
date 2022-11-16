<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Type;

/**
 * Repetition algorithm type.
 */
enum RepetitionAlgorithm: string
{
    case Leitner = 'leitner';

    /**
     * Coerce value to enum.
     */
    public static function coerce(RepetitionAlgorithm|string $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::from($value);
    }
}
