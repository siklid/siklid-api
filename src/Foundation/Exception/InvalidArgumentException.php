<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

class InvalidArgumentException extends SiklidException
{
    public static function create(string $expected, string $got): self
    {
        return new self(
            sprintf(
                'Expected instance of %s, got %s.',
                $expected,
                $got
            )
        );
    }
}
