<?php

declare(strict_types=1);

namespace App\Foundation\Security\Token;

use App\Foundation\Security\Token\TokenManagerInterface as Manager;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as Storage;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenSubscriber implements EventSubscriberInterface
{
    private Manager $manager;
    private Storage $storage;

    public function __construct(Manager $manager, Storage $storage)
    {
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function verifyToken(): void
    {
        $token = $this->storage->getToken();
        if (! $token instanceof JWTPostAuthenticationToken) {
            return;
        }

        $user = $token->getUser();
        assert($user instanceof UserInterface);

        if ($this->manager->isAccessTokenRevokedForUser($token->getCredentials(), $user)) {
            throw new ExpiredTokenException();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'verifyToken',
        ];
    }
}
