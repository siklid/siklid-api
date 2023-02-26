<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use App\Foundation\Security\Authentication\AccessTokenInterface;
use App\Foundation\Security\Authentication\HasAccessToken;
use App\Foundation\Validation\Constraint as AppAssert;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Application\Contract\Entity\UserInterface as SiklidUserInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as Authenticable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
#[MongoDB\Document(collection: 'users')]
#[AppAssert\UniqueDocument(fields: ['email'])]
#[AppAssert\UniqueDocument(fields: ['username'])]
class User implements SiklidUserInterface, Authenticable, UserInterface, HasAccessToken
{
    #[MongoDB\Id]
    #[Groups(['user:read', 'resource:read'])]
    private string $id;

    #[MongoDB\Field(type: 'email')]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read'])]
    private Email $email;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Groups(['_none'])]
    private string $password;

    #[MongoDB\Field(type: 'username')]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'resource:read'])]
    #[AppAssert\Username]
    private Username $username;

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

    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(Email $email): User
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

    public function getUsername(): Username
    {
        return $this->username;
    }

    /**
     * @return $this
     */
    public function setUsername(Username $username): User
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
        return $this->getEmail()->__toString();
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
