<?php

declare(strict_types=1);

namespace App\Foundation\ValueType;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * This type is used to map Enum values to MongoDB.
 */
class SpecificType extends Type
{
    use ClosureToPHP;
}
