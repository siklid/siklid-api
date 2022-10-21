<?php

declare(strict_types=1);

namespace App\Siklid\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @psalm-suppress MissingConstructor
 */
#[MongoDB\Document(collection: 'oauth_clients')]
class OAuthClient
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $name;

    #[MongoDB\Field(type: 'string')]
    #[Groups(['_none'])]
    private string $secret;

    /**
     * @var string|string[] a single redirect URI or an array of redirect URIs
     */
    #[MongoDB\Field(type: 'string', nullable: true)]
    private string|array $redirectUri = '';

    #[MongoDB\Field(type: 'bool')]
    private bool $personalAccessClient;

    #[MongoDB\Field(type: 'bool')]
    private bool $passwordClient;

    #[MongoDB\Field(type: 'bool')]
    private bool $revoked = false;

    protected bool $isConfidential = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): OAuthClient
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): OAuthClient
    {
        $this->name = $name;

        return $this;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): OAuthClient
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string|string[]
     */
    public function getRedirectUri(): string|array
    {
        return $this->redirectUri;
    }

    /**
     * @param string|string[] $redirectUri
     */
    public function setRedirectUri(string|array $redirectUri): OAuthClient
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function isPersonalAccessClient(): bool
    {
        return $this->personalAccessClient;
    }

    public function setPersonalAccessClient(bool $personalAccessClient): OAuthClient
    {
        $this->personalAccessClient = $personalAccessClient;

        return $this;
    }

    public function isPasswordClient(): bool
    {
        return $this->passwordClient;
    }

    public function setPasswordClient(bool $passwordClient): OAuthClient
    {
        $this->passwordClient = $passwordClient;

        return $this;
    }

    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    public function setRevoked(bool $revoked): OAuthClient
    {
        $this->revoked = $revoked;

        return $this;
    }

    /**
     * Returns true if the client is confidential.
     */
    public function isConfidential(): bool
    {
        return $this->isConfidential;
    }

    public function getIdentifier(): string
    {
        return $this->id;
    }
}
