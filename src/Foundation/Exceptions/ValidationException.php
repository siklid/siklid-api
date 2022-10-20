<?php

declare(strict_types=1);

namespace App\Foundation\Exceptions;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends SiklidException implements RenderableInterface
{
    protected ?FormErrorIterator $errorIterator = null;

    /**
     * @param FormErrorIterator<FormError> $errorIterator
     */
    public function setErrorIterator(FormErrorIterator $errorIterator): void
    {
        $this->errorIterator = $errorIterator;
    }

    /**
     * Get the response that should be returned.
     */
    public function render(): Response
    {
        return new JsonResponse([
            'message' => 'Invalid request',
            'errors' => $this->formatErrorMessages(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function formatErrorMessages(): array
    {
        $errors = [];

        if (null === $this->errorIterator) {
            return $errors;
        }

        foreach ($this->errorIterator as $error) {
            /** @psalm-suppress MixedAssignment, PossiblyUndefinedMethod, MixedMethodCall */
            $propertyPath = $error->getCause()->getPropertyPath();

            /** @psalm-suppress MixedAssignment, PossiblyUndefinedMethod */
            $errorMessage = $error->getMessage();

            /** @var string $propertyPath */
            if (! isset($errors[$propertyPath])) {
                $errors[$propertyPath] = [];
            }

            /** @psalm-suppress MixedAssignment */
            $errors[$propertyPath][] = $errorMessage;
        }

        return $errors;
    }
}
