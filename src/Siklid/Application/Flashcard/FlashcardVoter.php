<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Security\Authorization\AbstractVoter;
use App\Siklid\Application\Contract\Entity\FlashCardInterface;

final class FlashcardVoter extends AbstractVoter
{
    protected string $supportedClass = FlashCardInterface::class;
    protected array $supportedAttributes = [
        self::CREATE,
        self::READ,
    ];

    public function canCreate(): bool
    {
        return true;
    }

    public function canRead(): bool
    {
        return true;
    }
}
