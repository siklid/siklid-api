<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Siklid\Auth\Token\AccessTokenInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class AccessToken implements AccessTokenInterface
{
    #[Groups(['token:read'])]
    #[SerializedName('accessToken')]
    private string $token;

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
}
