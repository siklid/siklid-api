<?php

declare(strict_types=1);

namespace App\Foundation\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base controller for all API controllers.
 */
abstract class ApiController extends AbstractController
{
    /**
     * Returns a JSON response with a 200 status code.
     */
    public function ok(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, [
            'groups' => $groups,
        ]);

        return $this->json(compact('data'), Response::HTTP_OK, $headers, $context);
    }

    /**
     * Returns a JSON response with a 201 status code.
     */
    public function created(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, [
            'groups' => $groups,
        ]);

        return $this->json(compact('data'), Response::HTTP_CREATED, $headers, $context);
    }
}
