<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Application\Contract\Entity\UserInterface;
use App\Siklid\Application\Contract\Type\RepetitionAlgorithm;
use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[MongoDB\Document(collection: 'boxes')]
class Box implements BoxInterface
{
    #[MongoDB\Id]
    #[Groups(['box:read'])]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(['box:read'])]
    private string $name;

    #[MongoDB\Field(type: 'specific')]
    #[Groups(['box:read'])]
    private RepetitionAlgorithm $repetitionAlgorithm = RepetitionAlgorithm::Leitner;

    #[MongoDB\Field(type: 'string', nullable: true)]
    #[Groups(['box:read'])]
    private ?string $description = null;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Groups(['box:read'])]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Groups(['box:read'])]
    private DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    #[Groups(['box:read'])]
    private ?DateTimeImmutable $deletedAt = null;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private UserInterface $user;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): BoxInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): BoxInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): BoxInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): BoxInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): BoxInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): BoxInterface
    {
        $this->user = $user;

        return $this;
    }

    public function getRepetitionAlgorithm(): RepetitionAlgorithm
    {
        return $this->repetitionAlgorithm;
    }

    public function setRepetitionAlgorithm(RepetitionAlgorithm|string $repetitionAlgorithm): Box
    {
        $this->repetitionAlgorithm = RepetitionAlgorithm::coerce($repetitionAlgorithm);

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): Box
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
