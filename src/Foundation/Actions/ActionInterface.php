<?php

declare(strict_types=1);

namespace App\Foundation\Actions;

interface ActionInterface
{
    /**
     * Executes action.
     */
    public function execute(): mixed;
}
