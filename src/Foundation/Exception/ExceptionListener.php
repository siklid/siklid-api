<?php

declare(strict_types=1);

namespace App\Foundation\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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
        }

        if ($exception instanceof BadRequestHttpException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    $exception->getStatusCode(),
                    $exception->getHeaders()
                )
            );
        }
    }
}
