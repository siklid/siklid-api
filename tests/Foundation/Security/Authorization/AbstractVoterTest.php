<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Security\Authorization;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\Security\Authorization\AbstractVoter;
use App\Tests\TestCase;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractVoterTest extends TestCase
{
    /** @test */
    public function vote_with_empty_attributes(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [];
            protected ?string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);
        $subject = new stdClass();

        $actual = $sut->vote($token, $subject, []);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_with_empty_subject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The subject must be set.');

        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [];
            protected ?string $supportedClass = null;
        };
        $token = $this->createMock(TokenInterface::class);

        $sut->vote($token, null, []);
    }

    /** @test */
    public function vote_with_non_supported_attribute(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = ['foo'];
            protected ?string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);

        $actual = $sut->vote($token, new stdClass(), ['bar']);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_with_non_supported_subject(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = ['foo'];
            protected ?string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);

        $actual = $sut->vote(
            $token,
            new class() {
            },
            ['foo']
        );

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_on_a_grant_able_resource(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = ['foo'];
            protected ?string $supportedClass = stdClass::class;

            public function canFoo(stdClass $subject, UserInterface $token): bool
            {
                return true;
            }
        };
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createMock(UserInterface::class));

        $actual = $sut->vote($token, new stdClass(), ['foo']);

        $this->assertSame(VoterInterface::ACCESS_GRANTED, $actual);
    }

    /** @test */
    public function vote_on_a_non_accessible_resource(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = ['foo'];
            protected ?string $supportedClass = stdClass::class;

            public function canFoo(stdClass $subject, UserInterface $token): bool
            {
                return false;
            }
        };
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createMock(UserInterface::class));

        $actual = $sut->vote($token, new stdClass(), ['foo']);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $actual);
    }
}
