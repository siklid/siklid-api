<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authorization;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as SymfonyAuthChecker;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    private SymfonyAuthChecker $checker;

    public function __construct(SymfonyAuthChecker $checker)
    {
        $this->checker = $checker;
    }

    /**
     * {@inheritDoc}
     */
    public function isGranted(array|string $attribute, ?AuthorizableInterface $subject = null): bool
    {
        return $this->checker->isGranted($attribute, $subject);
    }

    /**
     * {@inheritDoc}
     */
    public function denyAccessUnlessGranted(array|string $attribute, AuthorizableInterface $subject): void
    {
        if (! $this->isGranted($attribute, $subject)) {
            $exception = new AccessDeniedException();
            $exception->setAttributes($attribute);
            $exception->setSubject($subject);
            throw $exception;
        }
    }
}
