<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use App\Foundation\Action\ConfigInterface;
use App\Foundation\Redis\Contract\SetInterface;
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
    private SetInterface $revokedTokens;
    private ConfigInterface $config;

    public function __construct(
        JwtManager $jwtManager,
        RefreshTokenManager $refreshTokenManager,
        SetInterface $revokedTokens,
        ConfigInterface $config
    ) {
        $this->jwtManager = $jwtManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->revokedTokens = $revokedTokens;
        $this->config = $config;
    }

    public function createAccessToken(UserInterface $user): AccessTokenInterface
    {
        $token = $this->jwtManager->create($user);
        $accessToken = new AccessToken($token);

        $refreshToken = $this->refreshTokenManager->createForUser($user, RefreshTokenManager::CONFIGURED_TTL);
        $accessToken->setRefreshToken($refreshToken);

        return $accessToken;
    }

    public function revokeAccessTokenForUser(string $accessToken, UserInterface $user): void
    {
        $key = sprintf(self::REVOKED_TOKENS_KEY_PATTERNS, $user->getUserIdentifier());

        $this->revokedTokens->add($key, $accessToken);

        $ttl = $this->config->get('@lexik_jwt_authentication.token_ttl');
        assert(is_int($ttl));

        $this->revokedTokens->setTtl($key, $ttl);
    }

    public function isAccessTokenRevokedForUser(string $accessToken, UserInterface $user): bool
    {
        $key = sprintf(self::REVOKED_TOKENS_KEY_PATTERNS, $user->getUserIdentifier());

        return $this->revokedTokens->contains($key, $accessToken);
    }
}
