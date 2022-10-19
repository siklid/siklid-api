<?php

declare(strict_types=1);

namespace App\Foundation\Actions;

interface ValidatableInterface
{
    /**
     * Returns data required for form submission.
     */
    public function formInput(): string|array|null;
}
