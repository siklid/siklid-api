<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException;

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

        throw new InvalidArgumentException('Value must be an instance of UnitEnum');
    }

    public function convertToPHPValue($value): CoercibleEnum
    {
        if (is_array($value) && isset($value['name'], $value['value'])) {
            /** @var class-string<CoercibleEnum> $name */
            $name = $value['name'];
            /** @var string $data */
            $data = $value['value'];

            return $this->toPHP($name, $data);
        }

        throw new InvalidArgumentException('Value must be an array with name and value keys');
    }

    /**
     * @param class-string<CoercibleEnum> $enum  full qualified name of the enum
     * @param string                      $value the value of the backed enum
     *
     * @return CoercibleEnum the enum instance
     */
    private function toPHP(string $enum, string $value): CoercibleEnum
    {
        return $enum::coerce($value);
    }
}
