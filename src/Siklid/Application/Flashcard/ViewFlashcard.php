<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Exception\SiklidException;
use App\Siklid\Application\Contract\Entity\FlashcardInterface;

class ViewFlashcard extends AbstractAction
{
    private ?FlashcardInterface $flashCard;

    public function __construct(?FlashcardInterface $flashCard = null)
    {
        $this->flashCard = $flashCard;
    }

    public function execute(): FlashcardInterface
    {
        if (null === $this->flashCard) {
            throw new SiklidException('Flashcard is not set');
        }

        return $this->flashCard;
    }

    public function setFlashcard(FlashcardInterface $flashCard): self
    {
        $this->flashCard = $flashCard;

        return $this;
    }
}
