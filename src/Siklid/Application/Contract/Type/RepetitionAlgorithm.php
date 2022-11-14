<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Type;

/**
 * Repetition algorithm type.
 */
enum RepetitionAlgorithm: string
{
    case Leitner = 'leitner';

    public static function coerce(RepetitionAlgorithm|string $repetitionAlgorithm): self
    {
        if ($repetitionAlgorithm instanceof self) {
            return $repetitionAlgorithm;
        }

        return self::from($repetitionAlgorithm);
    }
}
