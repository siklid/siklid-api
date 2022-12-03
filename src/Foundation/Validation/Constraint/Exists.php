<?php

declare(strict_types=1);

namespace App\Foundation\Validation\Constraint;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[Attribute]
class Exists extends Constraint
{
    public string $field;

    public ?string $document;

    /**
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    #[HasNamedArguments]
    public function __construct(
        ?string $document = null,
        string $field = 'id',
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);

        $this->document = $document;
        $this->field = $field;
    }

    public function validatedBy(): string
    {
        return ExistsValidator::class;
    }

    public function message(): string
    {
        return 'The document {{ document }} with {{ field }}: {{ value }} does not exist.';
    }
}
