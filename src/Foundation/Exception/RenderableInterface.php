<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

interface RenderableInterface extends Throwable
{
    /**
     * Get the response that should be returned.
     */
    public function render(): Response;
}
