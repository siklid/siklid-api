<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * This type is used to map Enum values to MongoDB.
 */
class SpecificType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): array
    {
        if ($value instanceof CoercibleEnum) {
            return $value->toArray();
        }

        throw InvalidArgumentException::create(CoercibleEnum::class, get_debug_type($value));
    }

    public function convertToPHPValue($value): CoercibleEnum
    {
        if (is_array($value) && isset($value['name'], $value['value'])) {
            /** @var class-string<CoercibleEnum> $name */
            $name = $value['name'];

            return $this->toPHP($name, $value['value']);
        }

        throw new InvalidArgumentException('Value must be an array with name and value keys.');
    }

    /**
     * @param class-string<CoercibleEnum> $enum  full qualified name of the enum
     * @param mixed                       $value the value of the backed enum
     *
     * @return CoercibleEnum the enum instance
     */
    private function toPHP(string $enum, mixed $value): CoercibleEnum
    {
        return $enum::coerce($value);
    }
}
