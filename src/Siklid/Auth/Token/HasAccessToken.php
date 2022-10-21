<?php

declare(strict_types=1);

namespace App\Siklid\Auth\Token;

interface HasAccessToken
{
    /**
     * Returns auth token if exists.
     *
     * @return AccessTokenInterface|null JWT token
     */
    public function getAccessToken(): ?AccessTokenInterface;
}
