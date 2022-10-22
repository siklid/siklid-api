<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Siklid\Application\Auth\Token\AccessTokenInterface;
use App\Siklid\Application\Auth\Token\HasAccessToken;
use App\Siklid\Foundation\Constraint\UniqueDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as Authenticable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
#[MongoDB\Document(collection: 'users')]
#[UniqueDocument(fields: ['email'])]
#[UniqueDocument(fields: ['username'])]
class User implements Authenticable, UserInterface, HasAccessToken
{
    #[MongoDB\Id]
    #[Groups(['user:read'])]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read'])]
    private string $email;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(['_none'])]
    private string $password;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(['user:read'])]
    private string $username;

    private bool $shouldEraseCredentials = false;

    private ?AccessTokenInterface $accessToken = null;

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return $this
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return $this
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
        if ($this->shouldEraseCredentials) {
            $this->password = '';
        }
    }

    public function setShouldEraseCredentials(bool $shouldEraseCredentials): User
    {
        $this->shouldEraseCredentials = $shouldEraseCredentials;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getAccessToken(): ?AccessTokenInterface
    {
        return $this->accessToken;
    }

    public function setAccessToken(AccessTokenInterface|null $accessToken): User
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
