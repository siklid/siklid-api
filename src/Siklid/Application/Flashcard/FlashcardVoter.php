<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Security\Authorization\AbstractVoter;
use App\Siklid\Application\Contract\Entity\FlashcardInterface as Flashcard;
use App\Siklid\Application\Contract\Entity\UserInterface as User;

/**
 * @extends AbstractVoter<string, Flashcard>
 */
final class FlashcardVoter extends AbstractVoter
{
    /**
     * @var class-string the subject that this voter supports
     */
    protected string $supportedClass = Flashcard::class;

    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [
        self::CREATE,
        self::READ,
        self::UPDATE,
        self::DELETE,
    ];

    public function canCreate(): bool
    {
        return true;
    }

    public function canRead(): bool
    {
        return true;
    }

    public function canUpdate(Flashcard $flashcard, User $user): bool
    {
        return $flashcard->getUser()->getId() === $user->getId();
    }

    public function canDelete(Flashcard $flashcard, User $user): bool
    {
        return $flashcard->getUser()->getId() === $user->getId();
    }
}
