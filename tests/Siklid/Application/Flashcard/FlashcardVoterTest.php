<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Application\Flashcard;

use App\Siklid\Application\Contract\Entity\FlashcardInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;
use App\Siklid\Application\Flashcard\FlashcardVoter;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;

class FlashcardVoterTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function can_create(): void
    {
        $sut = new FlashcardVoter();

        $actual = $sut->canCreate();

        $this->assertTrue($actual);
    }

    /** @test */
    public function can_read(): void
    {
        $sut = new FlashcardVoter();

        $actual = $sut->canRead();

        $this->assertTrue($actual);
    }

    /**
     * @test
     *
     * @dataProvider can_update_provider
     */
    public function can_update(FlashcardInterface $flashcard, UserInterface $user, bool $expected): void
    {
        $sut = new FlashcardVoter();

        $actual = $sut->canUpdate($flashcard, $user);

        $this->assertSame($expected, $actual);
    }

    public function can_update_provider(): array
    {
        $firstUser = $this->createMock(UserInterface::class);
        $firstUser->method('getId')->willReturn('123456');
        $secondUser = $this->createMock(UserInterface::class);
        $secondUser->method('getId')->willReturn('654321');

        $flashcard = $this->createMock(FlashcardInterface::class);
        $flashcard->method('getUser')->willReturn($firstUser);

        return [
            'same user' => [$flashcard, $firstUser, true],
            'different user' => [$flashcard, $secondUser, false],
        ];
    }
}
