<?php
declare(strict_types=1);

namespace App\Tests\Unit\Siklid\Application\Auth;

use App\Foundation\Security\Token\TokenManagerInterface;
use App\Siklid\Application\Auth\LoginSuccessHandler;
use App\Siklid\Document\AccessToken;
use App\Siklid\Document\User;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor for this test
 */
class LoginSuccessHandlerTest extends TestCase
{
    /**
     * @test
     * @psalm-suppress MixedArgument - All used $faker-> methods return string
     */
    public function get_response_data(): void
    {
        // Given
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $sut = new LoginSuccessHandler($tokenManager);
        $container = $this->createMock(ContainerInterface::class);
        $sut->setContainer($container);

        $user = new User();
        $user->setId($this->faker->uuid());
        $user->setUsername($this->faker->userName());
        $user->setEmail($this->faker->email());
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
     * @psalm-suppress MixedArgument - All used $faker-> methods return string
     */
    public function on_authentication_success(): void
    {
        // Given
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $sut = new LoginSuccessHandler($tokenManager);
        $container = $this->createMock(ContainerInterface::class);
        $sut->setContainer($container);

        $user = new User();
        $user->setId($this->faker->uuid());
        $user->setUsername($this->faker->userName());
        $user->setEmail($this->faker->email());
        $user->setAccessToken(
            new AccessToken($this->faker->md5())
        );

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        // When
        $response = $sut->onAuthenticationSuccess(new Request(), $token);

        // Then
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('{"data":{"user":{},"token":{}}}', $response->getContent());
    }
}
