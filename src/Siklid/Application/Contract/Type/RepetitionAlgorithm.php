<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Type;

use App\Foundation\ValueType\CoercibleEnum;
use InvalidArgumentException;

/**
 * Repetition algorithm type.
 */
enum RepetitionAlgorithm: string implements CoercibleEnum
{
    case Leitner = 'leitner';

    public static function coerce(string|CoercibleEnum $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (! is_string($value)) {
            throw new InvalidArgumentException('Value must be a string or an instance of RepetitionAlgorithm');
        }

        return self::from($value);
    }

    public function toArray(): array
    {
        return [
            'name' => self::class,
            'value' => $this->value,
        ];
    }
}
