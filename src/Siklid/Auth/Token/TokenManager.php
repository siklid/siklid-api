<?php

declare(strict_types=1);

namespace App\Siklid\Auth\Token;

use App\Siklid\Document\AccessToken;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenManager implements TokenManagerInterface
{
    private JWTTokenManagerInterface $JWTTokenManager;

    private RefreshTokenGeneratorInterface $refreshTokenGenerator;

    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(
        JWTTokenManagerInterface $JWTTokenManager,
        RefreshTokenGeneratorInterface $refreshTokenGenerator,
        RefreshTokenManagerInterface $refreshTokenManager
    ) {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->refreshTokenGenerator = $refreshTokenGenerator;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function createAccessToken(UserInterface $user): AccessTokenInterface
    {
        $token = $this->JWTTokenManager->create($user);
        $accessToken = new AccessToken($token);

        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, 2592000);
        $this->refreshTokenManager->save($refreshToken);

        $accessToken->setRefreshToken($refreshToken);

        return $accessToken;
    }
}
