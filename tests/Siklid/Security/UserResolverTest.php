<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Security;

use App\Foundation\Exception\LogicException;
use App\Siklid\Document\User;
use App\Siklid\Security\UserResolver;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserResolverTest extends TestCase
{
    use KernelTestCaseTrait;

    /**
     * @test
     */
    public function get_user_throws_exception_with_no_active_user(): void
    {
        $this->expectException(LogicException::class);

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->container()->get('security.token_storage');
        $sut = new UserResolver($tokenStorage);

        $sut->getUser();
    }

    /**
     * @test
     */
    public function get_user_returns_the_current_user(): void
    {
        $user = new User();
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);
        $sut = new UserResolver($tokenStorage);

        $actual = $sut->getUser();

        $this->assertSame($user, $actual);
    }
}
