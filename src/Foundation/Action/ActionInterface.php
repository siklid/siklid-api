<?php

declare(strict_types=1);

namespace App\Foundation\Action;

interface ActionInterface
{
    /**
     * Executes action.
     */
    public function execute(): mixed;
}
