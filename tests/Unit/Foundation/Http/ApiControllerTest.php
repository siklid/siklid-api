<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Http;

use App\Foundation\Http\ApiController;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @psalm-suppress MissingConstructor
 */
class ApiControllerTest extends TestCase
{
    private ApiController $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->getMockForAbstractClass(ApiController::class);
        $this->sut->setContainer($this->createMock(ContainerInterface::class));
    }

    /**
     * @test
     */
    public function ok(): void
    {
        $data = ['foo' => 'bar'];

        $actual = $this->sut->ok($data);

        $this->assertSame(Response::HTTP_OK, $actual->getStatusCode());
        $content = $this->json->jsonToArray((string)$actual->getContent());
        $this->assertSame($data, $content['data']);
    }

    /**
     * @test
     */
    public function created(): void
    {
        $data = ['foo' => 'bar'];

        $actual = $this->sut->created($data);

        $this->assertSame(Response::HTTP_CREATED, $actual->getStatusCode());
        $content = $this->json->jsonToArray((string)$actual->getContent());
        $this->assertSame($data, $content['data']);
    }
}
