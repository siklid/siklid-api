<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Entity;

interface FlashcardInterface
{
    public function getUser(): UserInterface;
}
