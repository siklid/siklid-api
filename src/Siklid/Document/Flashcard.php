<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Foundation\Util\ClockFactory;
use App\Siklid\Application\Contract\Entity\FlashcardInterface;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use StellaMaris\Clock\ClockInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[MongoDB\Document(collection: 'cards')]
#[MongoDB\HasLifecycleCallbacks]
class Flashcard implements FlashcardInterface
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $frontside;

    #[MongoDB\Field(type: 'string')]
    private string $backside;

    #[MongoDB\Field(type: 'date')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date')]
    private DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'date')]
    private ?DateTimeImmutable $deletedAt = null;

    private ClockInterface $clock;

    public function __construct(?ClockInterface $clock = null)
    {
        $this->clock = $clock ?? ClockFactory::create();

        $this->createdAt = $this->clock->now();
        $this->updatedAt = $this->clock->now();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Flashcard
    {
        $this->id = $id;

        return $this;
    }

    public function getFrontside(): string
    {
        return $this->frontside;
    }

    public function setFrontside(string $frontside): Flashcard
    {
        $this->frontside = $frontside;

        return $this;
    }

    public function getBackside(): string
    {
        return $this->backside;
    }

    public function setBackside(string $backside): Flashcard
    {
        $this->backside = $backside;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): Flashcard
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): Flashcard
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): Flashcard
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    #[MongoDB\PrePersist]
    #[MongoDB\PreUpdate]
    public function touch(): void
    {
        $this->updatedAt = $this->clock->now();
    }

    #[MongoDB\PostLoad]
    public function setClock(?ClockInterface $clock = null): Flashcard
    {
        $this->clock = $clock ?? ClockFactory::create();

        return $this;
    }
}
