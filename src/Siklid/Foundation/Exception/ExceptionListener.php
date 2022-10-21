<?php

declare(strict_types=1);

namespace App\Siklid\Foundation\Exception;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
    }
}
