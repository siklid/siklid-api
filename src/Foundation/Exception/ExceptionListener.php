<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ExceptionListener
 * This class is used to listen to exceptions and handle them accordingly.
 */
final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof RenderableInterface) {
            $event->setResponse($exception->render());

            return;
        }

        if ($exception instanceof BadRequestHttpException) {
            $this->handleBadRequestException($event, $exception);

            return;
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $this->handleAccessDeniedException($event, $exception);
        }
    }

    private function handleBadRequestException(ExceptionEvent $event, BadRequestHttpException $exception): void
    {
        $event->setResponse(
            new JsonResponse(
                ['message' => $exception->getMessage()],
                $exception->getStatusCode(),
                $exception->getHeaders()
            )
        );
    }

    private function handleAccessDeniedException(ExceptionEvent $event, AccessDeniedHttpException $exception): void
    {
        $event->setResponse(
            new JsonResponse(
                ['message' => 'Access denied'],
                $exception->getStatusCode(),
                $exception->getHeaders()
            )
        );
    }
}
