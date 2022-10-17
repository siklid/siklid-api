<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Siklid\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;

class RegisterByEmail
{
    public function __construct(private readonly DocumentManager $dm)
    {
    }

    public function execute(): User
    {
        $user = new User();

        $user->setEmail('fake@email.com');
        $user->setPassword('fake_password');
        $user->setUsername('fake_username');

        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }
}
