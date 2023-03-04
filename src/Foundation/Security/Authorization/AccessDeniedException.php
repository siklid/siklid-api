<?php

declare(strict_types=1);

namespace App\Foundation\Security\Authorization;

use App\Foundation\Exception\RenderableInterface;
use App\Foundation\Exception\SiklidException;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AccessDeniedException extends SiklidException implements RenderableInterface
{
    /**
     * @var string[] The attributes that were denied access to
     */
    private array $attributes = [];

    /**
     * @var AuthorizableInterface|null The subject that was denied access to
     *                                 On runtime, it should be set to a non-null value. This is just to make it the same as Symfony's AccessDeniedException
     */
    private AuthorizableInterface|null $subject = null;

    public function __construct(string $message = 'Access Denied.', Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string[]|string $attributes
     */
    public function setAttributes(array|string $attributes): void
    {
        $this->attributes = (array)$attributes;
    }

    public function getSubject(): AuthorizableInterface
    {
        $subject = $this->subject;
        assert(! is_null($subject), new LogicException('Subject is not set'));

        return $subject;
    }

    public function setSubject(AuthorizableInterface $subject): void
    {
        $this->subject = $subject;
    }

    public function render(): Response
    {
        $responseBody = [
            'message' => $this->getMessage(),
            'errors' => [],
        ];

        $subject = $this->getSubject();
        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            $resourceName = $subject->getHumanReadableName();
            $responseBody['errors'][$subject->getKeyName()][] = ucfirst(strtolower("You are not allowed to $attribute this $resourceName."));
        }

        return new JsonResponse($responseBody, Response::HTTP_FORBIDDEN);
    }
}
