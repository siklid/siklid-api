<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Token;

use App\Foundation\Action\ConfigInterface;
use App\Foundation\Redis\Contract\SetInterface;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Foundation\ValueObject\Email;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Document\User;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class TokenManagerTest extends TestCase
{
    use KernelTestCaseTrait;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->touchCollection(RefreshToken::class);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment The faker email is a string
     * @psalm-suppress MixedArgument The faker email is a string
     */
    public function create_access_token(): void
    {
        $container = $this->container();
        $user = new User();
        $email = $this->faker->email();
        $user->setEmail(Email::fromString($email));
        $sut = $container->get(TokenManagerInterface::class);

        $accessToken = $sut->createAccessToken($user);

        $this->assertNotNull($accessToken->getToken());
        $this->assertExists(RefreshToken::class, ['username' => $user->getUserIdentifier()]);
    }

    /**
     * @test
     */
    public function revoke_access_token_for_user(): void
    {
        $container = $this->container();
        $user = new User();
        $email = $this->faker->email();
        $user->setEmail(Email::fromString($email));
        $sut = $container->get(TokenManagerInterface::class);
        $accessToken = $this->faker->sha256();

        $sut->revokeAccessTokenForUser($accessToken, $user);

        $revokedTokensSet = $container->get(SetInterface::class);
        $key = sprintf(TokenManagerInterface::REVOKED_TOKENS_KEY_PATTERNS, $user->getUserIdentifier());
        $this->assertTrue($revokedTokensSet->contains($key, $accessToken));
        $config = $container->get(ConfigInterface::class);
        $ttl = (int)$config->get('@lexik_jwt_authentication.token_ttl');
        $this->assertTrue($revokedTokensSet->getTtl($key) <= $ttl);
    }
}
