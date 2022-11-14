<?php

declare(strict_types=1);

namespace App\Siklid\Application\Contract\Entity;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;

/**
 * The user entity interface is used to define the Siklid application user entity.
 */
interface UserInterface
{
    public function getId(): string;

    public function getEmail(): Email;

    public function getPassword(): string;

    public function getUsername(): Username;
}
