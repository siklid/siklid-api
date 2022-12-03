<?php

declare(strict_types=1);

namespace App\Foundation\Validation\Constraint;

use Doctrine\ODM\MongoDB\DocumentManager;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistsValidator extends ConstraintValidator
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress MixedAssignment - User should know about the types.
     * @psalm-suppress MixedMethodCall - It's a getter method.
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (empty($value)) {
            return;
        }

        if (! $constraint instanceof Exists) {
            throw new UnexpectedTypeException($constraint, Exists::class);
        }

        if (empty($constraint->document)) {
            throw new InvalidArgumentException('Document class must be specified.');
        }

        if (empty($constraint->field)) {
            throw new InvalidArgumentException('Field must be specified.');
        }

        if (! class_exists($constraint->document)) {
            throw new InvalidArgumentException(sprintf('Document class "%s" does not exist.', $constraint->document));
        }

        if ($value instanceof $constraint->document) {
            $getter = 'get'.ucfirst($constraint->field);
            $value = $value->$getter();
        }

        $document = $this->dm->getRepository($constraint->document)->findOneBy([
            $constraint->field => $value,
        ]);

        if (null === $document) {
            $this->context->buildViolation($constraint->message())
                ->setParameter('{{ document }}', $constraint->document)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', (string)$value)
                ->addViolation();
        }
    }
}
