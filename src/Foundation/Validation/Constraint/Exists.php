<?php

declare(strict_types=1);

namespace App\Foundation\Validation\Constraint;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Exists extends Constraint
{
    public string $field = 'id';

    public ?string $document = null;

    public function validatedBy(): string
    {
        return ExistsValidator::class;
    }

    public function message(): string
    {
        return 'The document {{ document }} with {{ field }}: {{ value }} does not exist.';
    }
}
