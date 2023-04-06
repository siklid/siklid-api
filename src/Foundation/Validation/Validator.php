<?php

declare(strict_types=1);

namespace App\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use App\Foundation\Validation\ValidatorInterface as AppValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This validator is a wrapper around the Symfony validator.
 * It will throw a ValidationException when validation fails.
 */
final class Validator implements AppValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function abortUnlessValid(
        mixed $value,
        Constraint|array $constraints = null,
        string|GroupSequence|array $groups =
        null
    ): void {
        $violations = $this->validator->validate(
            $value,
            $constraints,
            $groups
        );

        if ($violations->count() > 0) {
            $validationException = new ValidationException();
            $validationException->setViolationList($violations);
            throw $validationException;
        }
    }
}
