<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Foundation\Security\Token\AccessTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class AccessToken implements AccessTokenInterface
{
    #[Groups(['token:read'])]
    #[SerializedName('accessToken')]
    private string $token;

    private RefreshTokenInterface|null $refreshToken = null;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): AccessToken
    {
        $this->token = $token;

        return $this;
    }

    #[Groups(['token:read'])]
    public function getExpiresAt(): int
    {
        return time() + 3600;
    }

    #[Groups(['token:read'])]
    public function getTokenType(): string
    {
        return 'Bearer';
    }

    public function getRefreshToken(): RefreshTokenInterface|null
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(RefreshTokenInterface|null $refreshToken): AccessToken
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Custom serialization for the refresh token.
     *
     * @return string|null the refresh token string if exists
     */
    #[Groups(['token:read'])]
    #[SerializedName('refreshToken')]
    public function getTokenRefresher(): string|null
    {
        return $this->refreshToken?->getRefreshToken();
    }
}
