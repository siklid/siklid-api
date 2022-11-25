<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Entity;

use DateTimeImmutable;

interface FlashcardInterface
{
    public function getId(): string;

    public function getFrontside(): string;

    public function getBackside(): string;

    public function getCreatedAt(): DateTimeImmutable;

    public function getUpdatedAt(): DateTimeImmutable;

    public function getDeletedAt(): ?DateTimeImmutable;
}
