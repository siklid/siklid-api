<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Siklid\Application\Contract\Entity\FlashCardInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[MongoDB\Document(collection: 'flashcards')]
class Flashcard implements FlashCardInterface
{
    #[MongoDB\Id]
    #[Groups(['flashcard:read', 'flashcard:delete', 'flashcard:create', 'flashcard:index'])]
    private string $id;

    #[MongoDB\Field(type: 'string', nullable: true)]
    #[Groups(['flashcard:read', 'flashcard:create', 'flashcard:index'])]
    private ?string $front = null;

    #[MongoDB\Field(type: 'string')]
    #[Groups(['flashcard:read', 'flashcard:create', 'flashcard:index'])]
    #[Assert\NotBlank]
    private string $back;

    #[MongoDB\ReferenceMany(targetDocument: Box::class)]
    #[Groups(['flashcard:read', 'flashcard:create', 'flashcard:index'])]
    #[Assert\Count(min: 1)]
    private Collection $boxes;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    #[Groups(['flashcard:read', 'flashcard:index', 'flashcard:create'])]
    private UserInterface $user;

    #[MongoDB\Field(type: 'date')]
    #[Groups(['flashcard:read', 'flashcard:index'])]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date')]
    #[Groups(['flashcard:read', 'flashcard:index'])]
    private DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'date', nullable: true)]
    #[Groups(['flashcard:read', 'flashcard:index'])]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->boxes = new ArrayCollection();
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

    public function getFront(): ?string
    {
        return $this->front;
    }

    public function setFront(?string $front): Flashcard
    {
        $this->front = $front;

        return $this;
    }

    public function getBack(): string
    {
        return $this->back;
    }

    public function setBack(string $back): Flashcard
    {
        $this->back = $back;

        return $this;
    }

    public function getBoxes(): Collection
    {
        return $this->boxes;
    }

    public function setBoxes(Collection $boxes): Flashcard
    {
        $this->boxes = $boxes;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): Flashcard
    {
        $this->user = $user;

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
}
