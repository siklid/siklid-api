<?php

declare(strict_types=1);

namespace App\Siklid\Auth\Token;

use Symfony\Component\Security\Core\User\UserInterface;

interface TokenManagerInterface
{
    /**
     * Creates a new token for the given user.
     *
     * @param UserInterface $user the user to create a token for
     */
    public function createAccessToken(UserInterface $user): AccessTokenInterface;
}
