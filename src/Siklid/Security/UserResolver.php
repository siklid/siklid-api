<?php

declare(strict_types=1);

namespace App\Siklid\Security;

use App\Siklid\Application\Contract\Entity\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResolver implements UserResolverInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        assert($user instanceof UserInterface || null === $user);

        return $user;
    }
}
