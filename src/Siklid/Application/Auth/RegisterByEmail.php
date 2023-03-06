<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Security\Authentication\TokenManagerInterface;
use App\Siklid\Application\Auth\Request\RegisterRequest;
use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hash;

final class RegisterByEmail extends AbstractAction
{
    private RegisterRequest $request;

    private DocumentManager $dm;

    private Hash $hash;

    private TokenManagerInterface $tokenManager;

    public function __construct(
        RegisterRequest $request,
        DocumentManager $dm,
        Hash $hash,
        TokenManagerInterface $tokenManager,
    ) {
        $this->request = $request;
        $this->dm = $dm;
        $this->hash = $hash;
        $this->tokenManager = $tokenManager;
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = $this->fill(User::class, $this->request->formInput());
        $user->setPassword($this->hash->hashPassword($user, $user->getPassword()));

        $this->dm->persist($user);
        $this->dm->flush();

        $accessToken = $this->tokenManager->createAccessToken($user);
        $user->setAccessToken($accessToken);

        return $user;
    }
}
