<?php

namespace App\Foundation\ValueObject;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class SlugType extends Type
{
    use ClosureToPHP;

    public const NAME = 'slug';

    public function convertToDatabaseValue($value): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value): ?Slug
    {
        return is_string($value) ? Slug::fromString($value) : null;
    }
}
