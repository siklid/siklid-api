<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Security\Authorization\AbstractVoter;
use App\Siklid\Application\Contract\Entity\FlashCardInterface;

final class FlashcardVoter extends AbstractVoter
{
    protected string $supportedClass = FlashCardInterface::class;
    protected array $supportedAttributes = [self::CREATE];

    public function canCreate(): bool
    {
        return true;
    }
}
