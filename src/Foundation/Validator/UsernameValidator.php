<?php

declare(strict_types=1);

namespace App\Foundation\Validator;

use App\Foundation\Constraint\Username as ConstraintUsername;
use App\Foundation\ValueObject\Username;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UsernameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof ConstraintUsername) {
            throw new UnexpectedTypeException($constraint, ConstraintUsername::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (! $this->isValidatable($value)) {
            throw new UnexpectedValueException($value, sprintf('string or %s', Username::class));
        }

        $value = $value instanceof Username ? $value->original() : (string)$value;

        if (! $this->isUsername($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    private function isValidatable(mixed $value): bool
    {
        return is_string($value) || $value instanceof Username;
    }

    private function isUsername(string $value): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9_.-]+$/', $value);
    }
}
