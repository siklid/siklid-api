<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Application\Flashcard;

use App\Siklid\Application\Flashcard\FlashcardVoter;
use App\Tests\TestCase;

class FlashcardVoterTest extends TestCase
{
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
}
