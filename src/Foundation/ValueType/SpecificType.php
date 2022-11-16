<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException;
use UnitEnum;

/**
 * This type is used to map Enum values to MongoDB.
 */
class SpecificType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): string
    {
        if ($value instanceof UnitEnum) {
            return (string)$value->value;
        }

        throw new InvalidArgumentException('Value must be an instance of UnitEnum');
    }
}
