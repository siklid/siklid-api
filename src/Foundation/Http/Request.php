<?php

declare(strict_types=1);

namespace App\Foundation\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Base request class.
 */
class Request
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    /**
     * Get current request.
     *
     * @psalm-suppress NullableReturnStatement - we know that request is not null
     * @psalm-suppress InvalidNullableReturnType - we know that request is not null
     */
    public function request(): SymfonyRequest
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Returns all request parameters.
     */
    public function all(): array
    {
        return $this->request()->request->all();
    }
}
