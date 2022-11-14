<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[MongoDB\Document(collection: 'boxes')]
class Box
{
    #[MongoDB\Id]
    #[Groups(['box:read'])]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(['box:read'])]
    private string $name;

    #[MongoDB\Field(type: 'string', nullable: true)]
    #[Groups(['box:read'])]
    private ?string $description = null;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Groups(['box:read'])]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable')]
    #[Groups(['box:read'])]
    private DateTimeImmutable $updatedAt;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private User $user;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Box
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Box
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Box
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): Box
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): Box
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Box
    {
        $this->user = $user;

        return $this;
    }
}
