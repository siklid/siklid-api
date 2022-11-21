<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Document\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * This trait is used to create users for testing purposes.
 */
trait UserFactoryTrait
{
    use FactoryConcern;

    protected function makeUser(array $attributes = []): User
    {
        $user = new User();

        $user->setEmail($attributes['email'] ?? Email::fromString($this->faker->email()));
        $user->setUsername($attributes['username'] ?? Username::fromString($this->faker()->userName()));
        $user->setPassword($attributes['password'] ?? $this->faker()->password());

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $this->container()->get('security.password_hasher');
        if ($passwordHasher->needsRehash($user)) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        }

        return $user;
    }
}
