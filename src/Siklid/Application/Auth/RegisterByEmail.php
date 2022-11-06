<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Siklid\Document\User;
use App\Siklid\Form\UserType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hash;

class RegisterByEmail extends AbstractAction
{
    private readonly Request $request;

    private readonly DocumentManager $dm;

    private Hash $hash;

    private TokenManagerInterface $tokenManager;

    public function __construct(
        Request $request,
        DocumentManager $dm,
        Hash $hash,
        TokenManagerInterface $tokenManager
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
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $this->validate($form, $this->request);

        $user->setPassword($this->hash->hashPassword($user, $user->getPassword()));

        $this->dm->persist($user);
        $this->dm->flush();

        $accessToken = $this->tokenManager->createAccessToken($user);
        $user->setAccessToken($accessToken);

        return $user;
    }
}
