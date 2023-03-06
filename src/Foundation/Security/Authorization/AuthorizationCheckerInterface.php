<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authorization;

/**
 * All authorization checks should be done through this interface.
 */
interface AuthorizationCheckerInterface
{
    /**
     * Checks if the attribute is granted against the current authentication token and optionally supplied subject.
     *
     * @param string[]|string       $attribute A single or an array of attributes to vote on
     * @param AuthorizableInterface $subject   the subject to secure, The object the user wants to access
     */
    public function isGranted(array|string $attribute, AuthorizableInterface $subject): bool;

    /**
     * Throws an exception if the attribute is not granted against the current authentication token and optionally supplied subject.
     *
     * @param string[]|string       $attribute A single or an array of attributes to vote on
     * @param AuthorizableInterface $subject   the subject to secure, The object the user wants to access
     *
     * @throws AccessDeniedException if access is not granted
     */
    public function denyAccessUnlessGranted(array|string $attribute, AuthorizableInterface $subject): void;
}
