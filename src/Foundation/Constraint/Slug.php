<?php

declare(strict_types=1);

namespace App\Foundation\Constraint;

use App\Foundation\Validator\SlugValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Slug extends Constraint
{
    public function validatedBy(): string
    {
        return SlugValidator::class;
    }

    public function message(): string
    {
        return 'The string "{{ string }}" is not a valid slug.';
    }
}
