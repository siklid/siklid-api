<?php

declare(strict_types=1);

namespace App\Tests\Integration\Foundation\Security\Token;

use App\Foundation\Security\Token\TokenManagerInterface;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Document\User;
use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class TokenManagerTest extends IntegrationTestCase
{
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
        $user->setEmail($email);

        $sut = $container->get(TokenManagerInterface::class);

        $accessToken = $sut->createAccessToken($user);
        $this->assertNotNull($accessToken->getToken());
        $this->assertExists(RefreshToken::class, ['username' => $user->getEmail()]);
    }
}