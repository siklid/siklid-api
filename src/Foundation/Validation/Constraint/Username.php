<?php

declare(strict_types=1);

namespace App\Foundation\Validation\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Username extends Constraint
{
    public function validatedBy(): string
    {
        return UsernameValidator::class;
    }

    public function message(): string
    {
        return 'The string "{{ string }}" is not a valid username.';
    }
}
