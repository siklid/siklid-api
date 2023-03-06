<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authorization;

interface AuthorizableInterface
{
    /**
     * Returns the human-readable name of the subject.
     */
    public function getHumanReadableName(): string;

    /**
     * Returns a key that could be used as an array key.
     */
    public function getKeyName(): string;
}
