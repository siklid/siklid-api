<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Foundation\Validation\ValidatorInterface;
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

    private ValidatorInterface $validator;

    public function __construct(
        RegisterRequest $request,
        DocumentManager $dm,
        Hash $hash,
        TokenManagerInterface $tokenManager,
        ValidatorInterface $validator
    ) {
        $this->request = $request;
        $this->dm = $dm;
        $this->hash = $hash;
        $this->tokenManager = $tokenManager;
        $this->validator = $validator;
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = $this->fill(User::class, $this->request->formInput());
        $user->setPassword($this->hash->hashPassword($user, $user->getPassword()));

        $this->validator->validate($user);

        $this->dm->persist($user);
        $this->dm->flush();

        $accessToken = $this->tokenManager->createAccessToken($user);
        $user->setAccessToken($accessToken);

        return $user;
    }
}
