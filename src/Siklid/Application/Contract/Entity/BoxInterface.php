<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Entity;

use App\Siklid\Application\Contract\Type\RepetitionAlgorithm;
use DateTimeImmutable;

/**
 * Box Entity Interface.
 */
interface BoxInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getDescription(): ?string;

    public function getRepetitionAlgorithm(): RepetitionAlgorithm;

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): BoxInterface;

    public function getCreatedAt(): DateTimeImmutable;

    public function getUpdatedAt(): DateTimeImmutable;

    public function getDeletedAt(): ?DateTimeImmutable;
}
