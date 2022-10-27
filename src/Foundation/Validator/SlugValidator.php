<?php

declare(strict_types=1);

namespace App\Foundation\Validator;

use App\Foundation\Constraint\Slug as ConstraintSlug;
use App\Foundation\ValueObject\Slug;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SlugValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintSlug) {
            throw new UnexpectedTypeException($constraint, ConstraintSlug::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->isValidatable($value)) {
            throw new UnexpectedValueException($value, sprintf('string or %s', Slug::class));
        }

        $value = $value instanceof Slug ? $value->original() : (string)$value;

        if (!$this->isSlug($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    private function isValidatable(mixed $value): bool
    {
        return is_string($value) || $value instanceof Slug;
    }

    private function isSlug(string $value): bool
    {
        return 1 === preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
    }
}
