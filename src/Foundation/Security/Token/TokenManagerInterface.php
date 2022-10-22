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
    /**
     * Creates a new token for the given user.
     *
     * @param UserInterface $user the user to create a token for
     */
    public function createAccessToken(UserInterface $user): AccessTokenInterface;
}
