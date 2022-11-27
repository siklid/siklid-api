<?php

declare(strict_types=1);

namespace App\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This validator is a wrapper around the Symfony validator.
 * It will throw a ValidationException when validation fails.
 */
class Validator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates a value against a constraint or a list of constraints.
     *
     * @param Constraint|Constraint[]                               $constraints The constraint(s) to validate against
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups      The validation groups to validate. If
     *                                                                           none is given, "Default" is assumed
     */
    public function validate(
        mixed $value,
        Constraint|array $constraints = null,
        string|GroupSequence|array $groups =
        null
    ): ConstraintViolationListInterface {
        $violations = $this->validator->validate(
            $value,
            $constraints,
            $groups
        );

        $validationException = new ValidationException();
        $validationException->setViolationList($violations);

        if ($violations->count() > 0) {
            throw $validationException;
        }

        return $violations;
    }
}
