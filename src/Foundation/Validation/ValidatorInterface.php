<?php

declare(strict_types=1);

namespace App\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Validates a value against a constraint or a list of constraints.
 */
interface ValidatorInterface
{
    /**
     * Throws a ValidationException when validation fails.
     *
     * If no constraint is passed, the constraint
     * {@link \Symfony\Component\Validator\Constraints\Valid} is assumed.
     *
     * @param Constraint|Constraint[]                               $constraints The constraint(s) to validate against
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups      The validation groups to validate. If
     *                                                                           none is given, "Default" is assumed
     *
     * @return ConstraintViolationListInterface A list of constraint violations
     *                                          If the list is empty, validation
     *                                          succeeded
     *
     * @throws ValidationException When validation fails
     */
    public function stopUnlessValid(
        mixed $value,
        Constraint|array $constraints = null,
        string|GroupSequence|array $groups =
        null
    ): ConstraintViolationListInterface;
}
