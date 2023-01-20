<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Token manager interface.
 * All access token managers must implement this interface.
 */
interface TokenManagerInterface
{
    public const REVOKED_TOKENS_KEY_PATTERNS = 'user:%s:revoked-tokens';

    /**
     * Creates a new token for the given user.
     */
    public function createAccessToken(UserInterface $user): AccessTokenInterface;

    /**
     * Revokes the given token for the given user.
     */
    public function revokeAccessTokenForUser(string $accessToken, UserInterface $user): void;

    /**
     * Checks if the given token is revoked for the given user.
     */
    public function isAccessTokenRevokedForUser(string $accessToken, UserInterface $user): bool;
}
