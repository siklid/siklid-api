<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Foundation\Validation\ValidatorInterface;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hash;

final class RegisterByEmail extends AbstractAction
{
    private readonly Request $request;

    private readonly DocumentManager $dm;

    private Hash $hash;

    private TokenManagerInterface $tokenManager;

    private ValidatorInterface $validator;

    public function __construct(
        Request $request,
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
        $user = new User();
        $user->setEmail(Email::fromString((string)$this->request->get('email')));
        $user->setUsername(Username::fromString((string)$this->request->get('username')));
        $user->setPassword($this->hash->hashPassword($user, (string)$this->request->get('password')));

        $this->validator->validate($user);

        $this->dm->persist($user);
        $this->dm->flush();

        $accessToken = $this->tokenManager->createAccessToken($user);
        $user->setAccessToken($accessToken);

        return $user;
    }
}
