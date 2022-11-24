<?php

declare(strict_types=1);

namespace App\Tests\Integration\Foundation\Security\Token;

use App\Foundation\Security\Token\TokenManagerInterface;
use App\Foundation\ValueObject\Email;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Document\User;
use App\Tests\Concern\CreatesKernel;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class TokenManagerTest extends TestCase
{
    use CreatesKernel;
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
}
