<?php

declare(strict_types=1);

namespace App\Foundation\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Base controller for all API controllers.
 *
 * @todo: add tests for the api controller
 */
abstract class ApiController extends AbstractController
{
    public function created(mixed $data, array $groups = [], array $headers = [], array $context = []): JsonResponse
    {
        $context = array_replace_recursive($context, [
            'groups' => $groups,
        ]);

        return $this->json(compact('data'), 201, $headers, $context);
    }
}
