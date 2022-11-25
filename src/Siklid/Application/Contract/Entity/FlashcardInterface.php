<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Entity;

use DateTime;

interface FlashcardInterface
{
    public function getId(): string;

    public function getFrontSide(): string;

    public function getBackSide(): string;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;

    public function getDeletedAt(): ?DateTime;
}
