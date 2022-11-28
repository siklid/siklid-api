<?php

declare(strict_types=1);

namespace App\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This validator is a wrapper around the Symfony validator.
 * It will throw a ValidationException when validation fails.
 */
final class Validator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates a value against a constraint or a list of constraints.
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

        if ($violations->count() > 0) {
            $validationException = new ValidationException();
            $validationException->setViolationList($violations);
            throw $validationException;
        }

        return $violations;
    }

    /**
     * Returns the metadata for the given value.
     *
     * @throws NoSuchMetadataException If no metadata exists for the given value
     */
    public function getMetadataFor(mixed $value): MetadataInterface
    {
        return $this->validator->getMetadataFor($value);
    }

    /**
     * Returns whether the class is able to return metadata for the given value.
     */
    public function hasMetadataFor(mixed $value): bool
    {
        return $this->validator->hasMetadataFor($value);
    }

    /**
     * Validates a property of an object against the constraints specified
     * for this property.
     *
     * @param string                                                $propertyName The name of the validated property
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups       The validation groups to validate. If
     *                                                                            none is given, "Default" is assumed
     *
     * @return ConstraintViolationListInterface A list of constraint violations
     *                                          If the list is empty, validation
     *                                          succeeded
     */
    public function validateProperty(
        object $object,
        string $propertyName,
        array|GroupSequence|string $groups = null
    ): ConstraintViolationListInterface {
        return $this->validator->validateProperty(
            $object,
            $propertyName,
            $groups
        );
    }

    /**
     * Validates a value against the constraints specified for an object's
     * property.
     *
     * @param object|string                                         $objectOrClass The object or its class name
     * @param string                                                $propertyName  The name of the property
     * @param mixed                                                 $value         The value to validate against the
     *                                                                             property's constraints
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups        The validation groups to validate.
     *                                                                             If none is given, "Default" is
     *                                                                             assumed
     *
     * @return ConstraintViolationListInterface A list of constraint violations
     *                                          If the list is empty, validation
     *                                          succeeded
     */
    public function validatePropertyValue(
        object|string $objectOrClass,
        string $propertyName,
        mixed $value,
        array|GroupSequence|string $groups = null
    ): ConstraintViolationListInterface {
        return $this->validator->validatePropertyValue(
            $objectOrClass,
            $propertyName,
            $value,
            $groups
        );
    }

    /**
     * Starts a new validation context and returns a validator for that context.
     *
     * The returned validator collects all violations generated within its
     * context. You can access these violations with the
     * {@link ContextualValidatorInterface::getViolations()} method.
     */
    public function startContext(): ContextualValidatorInterface
    {
        return $this->validator->startContext();
    }

    /**
     * Returns a validator in the given execution context.
     *
     * The returned validator adds all generated violations to the given
     * context.
     */
    public function inContext(ExecutionContextInterface $context): ContextualValidatorInterface
    {
        return $this->validator->inContext($context);
    }
}
