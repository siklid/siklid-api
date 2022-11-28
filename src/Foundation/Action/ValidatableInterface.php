<?php

declare(strict_types=1);

namespace App\Foundation\Action;

interface ValidatableInterface
{
    /**
     * Returns data required for form submission.
     *
     * @return array<string, mixed>
     */
    public function formInput(): array;
}
