<?php

declare(strict_types=1);

namespace App\Siklid\Auth\Token;

interface AccessTokenInterface
{
    /**
     * Returns the token string.
     *
     * @return string JWT token
     */
    public function getToken(): string;
}
