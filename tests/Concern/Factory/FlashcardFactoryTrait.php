<?php

declare(strict_types=1);

namespace App\Tests\Concern\Factory;

use App\Siklid\Document\Flashcard;
use App\Siklid\Document\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This trait is used to create flashcards for testing purposes.
 */
trait FlashcardFactoryTrait
{
    use FactoryConcern;

    protected function makeFlashcard(array $attributes = []): Flashcard
    {
        $flashcard = new Flashcard();

        $flashcard->setUser($attributes['user']);
        $flashcard->setFront($attributes['front'] ?? $this->faker->sentence());
        $flashcard->setBack($attributes['back'] ?? $this->faker->sentence());
        $flashcard->setBoxes($attributes['boxes'] ?? new ArrayCollection());

        $this->touchCollection(Flashcard::class);

        return $flashcard;
    }
}
