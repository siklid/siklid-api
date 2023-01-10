<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use App\Foundation\Security\Token\RefreshTokenManagerInterface as RefreshTokenManager;
use App\Siklid\Document\AccessToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface as JwtManager;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * The default token manager.
 */
class TokenManager implements TokenManagerInterface
{
    private JwtManager $jwtManager;
    private RefreshTokenManager $refreshTokenManager;

    public function __construct(JwtManager $jwtManager, RefreshTokenManager $refreshTokenManager)
    {
        $this->jwtManager = $jwtManager;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function createAccessToken(UserInterface $user): AccessTokenInterface
    {
        $token = $this->jwtManager->create($user);
        $accessToken = new AccessToken($token);

        $refreshToken = $this->refreshTokenManager->createForUser($user, RefreshTokenManager::CONFIGURED_TTL);
        $accessToken->setRefreshToken($refreshToken);

        return $accessToken;
    }
}
