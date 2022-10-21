<?php

declare(strict_types=1);

namespace App\Siklid\Foundation\Action;

interface ActionInterface
{
    /**
     * Executes action.
     */
    public function execute(): mixed;
}
