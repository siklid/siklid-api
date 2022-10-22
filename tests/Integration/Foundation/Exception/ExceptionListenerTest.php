<?php

declare(strict_types=1);

namespace App\Tests\Integration\Foundation\Exception;

use App\Foundation\Exception\ExceptionListener;
use App\Foundation\Exception\RenderableInterface;
use App\Foundation\Exception\SiklidException;
use App\Tests\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ExceptionListenerTest extends IntegrationTestCase
{
    /**
     * @test
     *
     * @psalm-suppress PossiblyNullReference We know that event has a response
     */
    public function on_kernel_exception_adds_uses_response_from_renderable_exceptions(): void
    {
        $sut = $this->container()->get(ExceptionListener::class);

        $exception = new class() extends SiklidException implements RenderableInterface {
            private ?Response $response = null;

            public function render(): Response
            {
                return $this->response ?? new Response('FAILED');
            }

            public function getResponse(): ?Response
            {
                return $this->response;
            }

            public function setResponse(?Response $response): void
            {
                $this->response = $response;
            }
        };

        $exception->setResponse(new Response('SUCCESS'));

        $exceptionEvent = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            1,
            $exception
        );

        $sut->onKernelException($exceptionEvent);

        $this->assertTrue($exceptionEvent->hasResponse());
        $this->assertSame('SUCCESS', (string)$exceptionEvent->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function on_kernel_exception_does_not_add_a_response_if_the_exception_is_not_renderable(): void
    {
        $sut = $this->container()->get(ExceptionListener::class);
        $exceptionEvent = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            1,
            new SiklidException()
        );

        $sut->onKernelException($exceptionEvent);

        $this->assertFalse($exceptionEvent->hasResponse());
    }
}
