<?php

declare(strict_types=1);

namespace App\Foundation\Constraint;

use App\Foundation\Validator\SlugValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Slug extends Constraint
{
    public string $message = 'The string "{{ string }}" is not a valid slug.';

    public function validatedBy(): string
    {
        return SlugValidator::class;
    }
}
