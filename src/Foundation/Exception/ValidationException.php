<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class ValidationException
 * This exception should be thrown when a validation error occurs.
 */
class ValidationException extends SiklidException implements RenderableInterface
{
    protected ?ConstraintViolationListInterface $violationList = null;

    public function __construct(string $message = 'Invalid request', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setViolationList(?ConstraintViolationListInterface $violationList = null): ValidationException
    {
        $this->violationList = $violationList;

        return $this;
    }

    /**
     * Get the response that should be returned.
     */
    public function render(): Response
    {
        return new JsonResponse([
            'message' => $this->getMessage(),
            'errors' => $this->formatErrorMessages(),
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @psalm-suppress MixedAssignment Mixed assignment from error messages and paths
     * @psalm-suppress PossiblyUndefinedMethod Possibly undefined method error cause.
     * @psalm-suppress MixedMethodCall Mixed method call from error messages and paths
     * @psalm-suppress PossiblyUndefinedMethod Possibly undefined method error cause and messages
     */
    private function formatErrorMessages(): array
    {
        $errors = [];

        if (null !== $this->violationList) {
            foreach ($this->violationList as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $propertyPath = str_replace(['[', ']'], '', $propertyPath);
                $errors[$propertyPath][] = $violation->getMessage();
            }
        }

        return $errors;
    }
}
