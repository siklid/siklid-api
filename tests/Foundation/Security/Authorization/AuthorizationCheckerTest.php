<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Authorization;

use App\Foundation\Security\Authorization\AccessDeniedException;
use App\Foundation\Security\Authorization\AuthorizableInterface;
use App\Foundation\Security\Authorization\AuthorizationChecker;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthorizationCheckerTest extends TestCase
{
    use KernelTestCaseTrait;
    use WithFaker;

    /** @test */
    public function is_granted(): void
    {
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $subject = $this->createMock(AuthorizableInterface::class);
        $expected = $this->faker->randomElement([true, false]);
        $checker->expects($this->once())->method('isGranted')->with('view', $subject)->willReturn($expected);
        $sut = new AuthorizationChecker($checker);

        $actual = $sut->isGranted('view', $subject);

        $this->assertSame($expected, $actual);
    }

    /** @test */
    public function deny_access_unless_granted_with_authorized_user(): void
    {
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $subject = $this->createMock(AuthorizableInterface::class);
        $checker->expects($this->once())->method('isGranted')->with('view', $subject)->willReturn(true);
        $sut = new AuthorizationChecker($checker);

        $sut->denyAccessUnlessGranted('view', $subject);
    }

    /** @test */
    public function deny_access_unless_granted_with_un_authorized_user(): void
    {
        $this->expectException(AccessDeniedException::class);
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $subject = $this->createMock(AuthorizableInterface::class);
        $checker->expects($this->once())->method('isGranted')->with('view', $subject)->willReturn(false);
        $sut = new AuthorizationChecker($checker);

        $sut->denyAccessUnlessGranted('view', $subject);
    }

    /** @test */
    public function access_denied_exception_contains_subject_and_attributes(): void
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Access Denied.');
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $subject = $this->createMock(AuthorizableInterface::class);
        $checker->expects($this->once())->method('isGranted')->with('view', $subject)->willReturn(false);
        $sut = new AuthorizationChecker($checker);

        try {
            $sut->denyAccessUnlessGranted('view', $subject);
        } catch (AccessDeniedException $exception) {
            $this->assertSame($subject, $exception->getSubject());
            $this->assertSame(['view'], $exception->getAttributes());

            throw $exception;
        }
    }
}
