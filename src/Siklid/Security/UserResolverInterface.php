<?php

declare(strict_types=1);

namespace App\Siklid\Security;

use App\Siklid\Application\Contract\Entity\UserInterface;

interface UserResolverInterface
{
    /**
     * Returns current user.
     */
    public function getUser(): ?UserInterface;
}
