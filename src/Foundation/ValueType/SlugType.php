<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Slug;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class SlugType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): string
    {
        if (! $value instanceof Slug) {
            throw InvalidArgumentException::create(Slug::class, get_debug_type($value));
        }

        return (string)$value;
    }

    public function convertToPHPValue($value): Slug
    {
        if (null === $value) {
            throw new InvalidArgumentException('Null values should be skipped by Doctrine.');
        }

        return Slug::fromString($value);
    }
}
