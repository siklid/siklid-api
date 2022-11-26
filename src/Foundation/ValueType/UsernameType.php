<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Username;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class UsernameType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): string
    {
        if (! $value instanceof Username) {
            throw InvalidArgumentException::create('UsernameType', get_debug_type($value));
        }

        return (string)$value;
    }

    public function convertToPHPValue($value): Username
    {
        if (null === $value) {
            throw new InvalidArgumentException('Null values should be skipped by Doctrine.');
        }

        return Username::fromString((string)$value);
    }
}
