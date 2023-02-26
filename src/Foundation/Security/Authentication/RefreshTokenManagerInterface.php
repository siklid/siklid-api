<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authentication;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface as RefreshToken;
use Symfony\Component\Security\Core\User\UserInterface;

interface RefreshTokenManagerInterface
{
    public const CONFIGURED_TTL = 0;

    public function createForUser(UserInterface $user, int $ttl): RefreshToken;

    public function revoke(RefreshToken $refreshToken): void;
}
