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
    public function revokeAccessTokenForUser(AccessTokenInterface $accessToken, UserInterface $user): void;
}
