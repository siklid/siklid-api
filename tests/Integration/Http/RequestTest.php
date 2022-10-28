<?php

declare(strict_types=1);

namespace App\Tests\Integration\Http;

use App\Foundation\Http\Request as Sut;
use App\Foundation\Util\RequestUtil;
use App\Tests\IntegrationTestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @psalm-suppress MissingConstructor
 */
class RequestTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function request_get_current_request(): void
    {
        $requestStack = new RequestStack();
        $currentRequest = new Request();
        $requestStack->push($currentRequest);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertSame($currentRequest, $sut->request());
    }

    /**
     * @test
     */
    public function all_get_request_content_if_request_is_json(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContentType')->willReturn('json');
        $request->method('getContent')->willReturn('{"foo":"bar"}');
        $request->request = new InputBag();

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertSame(['foo' => 'bar'], $sut->all());
    }

    /**
     * @test
     */
    public function all_calls_underlying_request__all_if_request_is_not_json(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContentType')->willReturn('html');
        $request->method('getContent')->willReturn('[]');
        $request->request = new InputBag(['foo' => 'bar']);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertSame(['foo' => 'bar'], $sut->all());
    }

    /**
     * @test
     */
    public function is_json_returns_true_if_content_type_is_json(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContentType')->willReturn('json');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertTrue($sut->isJson());
    }

    /**
     * @test
     */
    public function is_json_returns_false_if_content_type_is_not_json(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContentType')->willReturn('html');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertFalse($sut->isJson());
    }

    /**
     * @test
     */
    public function form_input_returns_the_same_as_all(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContentType')->willReturn('json');
        $request->method('getContent')->willReturn('{"foo":"bar"}');
        $request->request = new InputBag();

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $sut = new Sut($requestStack, new RequestUtil($this->json));

        $this->assertSame($sut->all(), $sut->formInput());
    }
}
