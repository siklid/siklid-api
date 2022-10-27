<?php

declare(strict_types=1);

namespace App\Foundation\Constraint;

use App\Foundation\Validator\UsernameValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Username extends Constraint
{
    public string $message = 'The string "{{ string }}" is not a valid username.';

    public function validatedBy(): string
    {
        return UsernameValidator::class;
    }
}
