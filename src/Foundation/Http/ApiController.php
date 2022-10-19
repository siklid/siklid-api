<?php

declare(strict_types=1);

namespace App\Foundation\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Base controller for all API controllers.
 */
abstract class ApiController extends AbstractController
{
    public function created(mixed $data, array $headers = [], array $context = []): JsonResponse
    {
        return $this->json(compact('data'), 201, $headers, $context);
    }
}
