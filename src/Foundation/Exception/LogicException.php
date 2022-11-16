<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LogicException extends SiklidException implements RenderableInterface
{
    public function render(): Response
    {
        return new JsonResponse([
            'message' => $this->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
    }
}
