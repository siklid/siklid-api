<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Siklid\Auth\Forms\UserType;
use App\Siklid\Auth\Request\RegisterByEmailRequest as Request;
use App\Siklid\Document\AccessToken;
use App\Siklid\Document\User;
use App\Siklid\Foundation\Action\AbstractAction;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface as JWT;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hash;

class RegisterByEmail extends AbstractAction
{
    private readonly Request $request;

    private readonly DocumentManager $dm;

    private Hash $hash;

    private JWT $jwtManager;

    public function __construct(Request $request, DocumentManager $dm, Hash $hash, JWT $jwtManager)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->hash = $hash;
        $this->jwtManager = $jwtManager;
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

        $token = $this->jwtManager->create($user);
        $user->setAccessToken(new AccessToken($token));

        return $user;
    }
}
