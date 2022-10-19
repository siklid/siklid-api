<?php

declare(strict_types=1);

namespace App\Foundation\Constraints;

use Attribute;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * > The Unique Attribute from Doctrine MongoDB ODM shows a deprecation warning.
 * Constraint for the Unique Document validator.
 *
 * @Annotation
 *
 * @Target({"CLASS", "ANNOTATION"})
 * @psalm-suppress PossiblyNullArgument
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class UniqueDocument extends UniqueEntity
{
    public function __construct(
        array|string|null $fields,
        string $message = null,
        string $service = 'doctrine_odm.mongodb.unique',
        string $em = null,
        string $entityClass = null,
        string $repositoryMethod = null,
        string $errorPath = null,
        bool $ignoreNull = null,
        array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        parent::__construct(
            $fields,
            $message,
            $service,
            $em,
            $entityClass,
            $repositoryMethod,
            $errorPath,
            $ignoreNull,
            $groups,
            $payload,
            $options
        );
    }
}
