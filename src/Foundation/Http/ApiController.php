<?php

declare(strict_types=1);

namespace App\Foundation\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
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

    /**
     * Returns a JSON response with a 400 status code.
     */
    public function badRequest(mixed $body, array $headers = []): JsonResponse
    {
        return $this->json($body, Response::HTTP_BAD_REQUEST, $headers);
    }

    /**
     * Returns a pagination JSON response with a 200 status code.
     */
    public function cursorPaginate(
        array $data,
        array $groups = [],
        array $headers = [],
        array $context = []
    ): JsonResponse {
        $context = array_replace_recursive($context, [
            'groups' => $groups,
        ]);

        /** @var RequestStack $requestStack */
        $requestStack = $this->container->get('request_stack');
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $requestStack->getCurrentRequest();
        $links = ['self' => $request->getUri(), 'next' => null];

        /** @var string $cursorAccessor */
        $cursorAccessor = $context['cursorAccessor'] ?? 'getId';
        /** @var object|null $lastItem */
        $lastItem = end($data);

        if (is_object($lastItem) && method_exists($lastItem, $cursorAccessor)) {
            $query = $request->query->all();
            $query['cursor'] = (string)$lastItem->{$cursorAccessor}();
            $links['next'] = $request->getSchemeAndHttpHost().$request->getPathInfo().'?'.http_build_query($query);
        }

        $body = [
            'data' => $data,
            'links' => $links,
            'meta' => ['count' => count($data)],
        ];

        return $this->json($body, Response::HTTP_OK, $headers, $context);
    }
}
