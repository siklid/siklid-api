<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Exception;

use App\Foundation\Exception\ExceptionListener;
use App\Foundation\Exception\RenderableInterface;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ExceptionListenerTest extends TestCase
{
    /**
     * @test
     */
    public function on_kernel_exception_gets_response_from_renderable_exception(): void
    {
        $response = new Response();
        $exceptionMock = $this->createMock(RenderableInterface::class);
        $exceptionMock->expects($this->once())
            ->method('render')
            ->willReturn($response);

        $exceptionEvent = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            1,
            $exceptionMock
        );

        $sut = new ExceptionListener();

        $sut->onKernelException($exceptionEvent);

        $this->assertSame($response, $exceptionEvent->getResponse());
    }

    /**
     * @test
     */
    public function on_kernel_exception_gets_response_from_bad_request_http_exception(): void
    {
        $exception = new BadRequestHttpException('Bad request');
        $exceptionEvent = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            1,
            $exception
        );

        $sut = new ExceptionListener();

        $sut->onKernelException($exceptionEvent);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $exceptionEvent->getResponse()?->getStatusCode());
        $this->assertSame('{"message":"Bad request"}', $exceptionEvent->getResponse()?->getContent());
    }

    /**
     * @test
     */
    public function on_kernel_exception_gets_response_from_access_denied_exception(): void
    {
        $exception = new AccessDeniedHttpException('Access denied');
        $exceptionEvent = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            1,
            $exception
        );
        $sut = new ExceptionListener();

        $sut->onKernelException($exceptionEvent);

        $this->assertSame(Response::HTTP_FORBIDDEN, $exceptionEvent->getResponse()?->getStatusCode());
        $this->assertSame('{"message":"Access denied"}', $exceptionEvent->getResponse()?->getContent());
    }
}
