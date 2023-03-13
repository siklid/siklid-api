<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Application\Auth;

use App\Foundation\Security\Token\TokenManagerInterface;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Application\Auth\LoginSuccessHandler;
use App\Siklid\Document\AccessToken;
use App\Siklid\Document\User;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor for this test
 */
class LoginSuccessHandlerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     *
     * @psalm-suppress MixedArgument - All used $faker-> methods return string
     */
    public function get_response_data(): void
    {
        // Given
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $serializer = $this->createMock(Serializer::class);
        $sut = new LoginSuccessHandler($tokenManager, $serializer);
        $container = $this->createMock(ContainerInterface::class);
        $sut->setContainer($container);

        $user = new User();
        $user->setId($this->faker->uuid());
        $user->setUsername(Username::fromString($this->faker->userName()));
        $user->setEmail(Email::fromString($this->faker->email()));
        $user->setAccessToken(
            new AccessToken($this->faker->md5())
        );

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        // When
        $data = $sut->getResponseData($token);

        // Then
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertInstanceOf(User::class, $data['user']);
        $this->assertSame($user, $data['user']);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedArgument - All used $faker-> methods return string
     */
    public function on_authentication_success(): void
    {
        // Given
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $serializer = $this->createMock(Serializer::class);
        $serializer->expects($this->once())->method('normalize')->willReturn(['data' => ['user' => [], 'token' => []]]);
        $sut = new LoginSuccessHandler($tokenManager, $serializer);
        $container = $this->createMock(ContainerInterface::class);
        $sut->setContainer($container);

        $user = new User();
        $user->setId($this->faker->uuid());
        $user->setUsername(Username::fromString($this->faker->userName()));
        $user->setEmail(Email::fromString($this->faker->email()));
        $user->setAccessToken(
            new AccessToken($this->faker->md5())
        );

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        // When
        $response = $sut->onAuthenticationSuccess(new Request(), $token);

        // Then
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('{"data":{"data":{"user":[],"token":[]}}}', $response->getContent());
    }
}
