<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Authentication;

use App\Foundation\Security\Authentication\TokenManagerInterface;
use App\Foundation\Security\Authentication\TokenSubscriber;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function verify_token_is_subscribed_to_request_event(): void
    {
        $this->assertArrayHasKey('kernel.request', TokenSubscriber::getSubscribedEvents());
        $this->assertSame('verifyToken', TokenSubscriber::getSubscribedEvents()['kernel.request']);
    }

    /**
     * @test
     */
    public function verify_token(): void
    {
        $this->expectException(ExpiredTokenException::class);

        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $sut = new TokenSubscriber($tokenManager, $tokenStorage);

        $token = $this->createMock(JWTPostAuthenticationToken::class);
        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $user = $this->createMock(UserInterface::class);
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $tokenManager->expects($this->once())
            ->method('isAccessTokenRevokedForUser')
            ->with($token, $user)
            ->willReturn(true);

        $sut->verifyToken();
    }

    /**
     * @test
     */
    public function verify_token_should_pass_if_token_not_found(): void
    {
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $sut = new TokenSubscriber($tokenManager, $tokenStorage);

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn(null);

        $tokenManager->expects($this->never())
            ->method('isAccessTokenRevokedForUser');

        $sut->verifyToken();
    }

    /**
     * @test
     */
    public function verify_token_should_pass_if_token_is_not_revoked(): void
    {
        $tokenManager = $this->createMock(TokenManagerInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $sut = new TokenSubscriber($tokenManager, $tokenStorage);

        $token = $this->createMock(JWTPostAuthenticationToken::class);
        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $user = $this->createMock(UserInterface::class);
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $tokenManager->expects($this->once())
            ->method('isAccessTokenRevokedForUser')
            ->with($token, $user)
            ->willReturn(false);

        $sut->verifyToken();
    }
}
