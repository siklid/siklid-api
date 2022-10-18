<?php

declare(strict_types=1);

namespace App\Siklid\Auth;

use App\Foundation\Actions\AbstractAction;
use App\Siklid\Document\User;

class RegisterByEmail extends AbstractAction
{
    public function execute(): User
    {
        $user = new User();

        $user->setEmail('fake@email.com');
        $user->setPassword('fake_password');
        $user->setUsername('fake_username');

        $this->persist($user);
        $this->flush();

        return $user;
    }
}
