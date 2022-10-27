<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

class UsernameType extends Type
{
    use ClosureToPHP;

    public function convertToDatabaseValue($value): string
    {
        return (string)$value;
    }

    public function convertToPHPValue($value): ?Username
    {
        return is_string($value) ? Username::fromString($value) : null;
    }
}
