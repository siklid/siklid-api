<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use App\Foundation\Action\ConfigInterface as Config;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface as Generator;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface as RefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface as Manager;
use Symfony\Component\Security\Core\User\UserInterface;

class RefreshTokenManager implements RefreshTokenManagerInterface
{
    private Generator $generator;
    private Manager $manager;
    private Config $config;

    public function __construct(Generator $generator, Manager $manager, Config $config)
    {
        $this->generator = $generator;
        $this->manager = $manager;
        $this->config = $config;
    }

    public function createForUser(UserInterface $user, int $ttl = 0): RefreshToken
    {
        $ttl = $ttl > 0 ? $ttl : (int)$this->config->get('security.tokens.refresh_token_ttl');
        $refreshToken = $this->generator->createForUserWithTtl($user, $ttl);
        $this->manager->save($refreshToken);

        return $refreshToken;
    }
}
