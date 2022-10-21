<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Siklid\Auth\Forms\UserType;
use App\Siklid\Auth\Requests\RegisterByEmailRequest as Request;
use App\Siklid\Document\User;
use App\Siklid\Foundation\Action\AbstractAction;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterByEmail extends AbstractAction
{
    private readonly Request $request;

    private readonly DocumentManager $dm;

    private UserPasswordHasherInterface $hasher;

    public function __construct(Request $request, DocumentManager $dm, UserPasswordHasherInterface $hasher)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->hasher = $hasher;
    }

    /**
     * Executes action.
     */
    public function execute(): User
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $this->validate($form, $this->request);

        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));

        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }
}
