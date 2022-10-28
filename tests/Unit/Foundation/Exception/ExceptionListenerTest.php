<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Exception;

use App\Foundation\Exception\ExceptionListener;
use App\Foundation\Exception\RenderableInterface;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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
}
