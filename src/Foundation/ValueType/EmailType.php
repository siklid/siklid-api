<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Email;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class EmailType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): string
    {
        if (! $value instanceof Email) {
            throw InvalidArgumentException::create(Email::class, get_debug_type($value));
        }

        return (string)$value;
    }

    public function convertToPHPValue($value): Email
    {
        if (null === $value) {
            throw new InvalidArgumentException('Null value should be skipped by Doctrine.');
        }

        return Email::fromString((string)$value);
    }
}
