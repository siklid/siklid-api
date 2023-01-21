<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Stringable;

interface AccessTokenInterface extends Stringable
{
    /**
     * Returns the token string.
     *
     * @return string JWT token
     */
    public function getToken(): string;

    /**
     * Returns the refresh token associated with the access token.
     *
     * @return RefreshTokenInterface|null The refresh token if exists
     */
    public function getRefreshToken(): RefreshTokenInterface|null;
}
