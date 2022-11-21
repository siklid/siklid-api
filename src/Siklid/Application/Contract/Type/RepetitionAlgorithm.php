<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Type;

use App\Foundation\ValueType\CoercibleEnum;

/**
 * Repetition algorithm type.
 */
enum RepetitionAlgorithm: string implements CoercibleEnum
{
    case Leitner = 'leitner';

    public static function coerce(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::from((string)$value);
    }

    public function toArray(): array
    {
        return [
            'name' => self::class,
            'value' => $this->value,
        ];
    }
}
