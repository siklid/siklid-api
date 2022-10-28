<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Http;

use App\Foundation\Http\ApiController;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ApiControllerTest extends TestCase
{
    /**
     * @test
     */
    public function created(): void
    {
        $sut = $this->getMockForAbstractClass(ApiController::class);
        $sut->setContainer($this->createMock(ContainerInterface::class));
        $data = ['foo' => 'bar'];

        $actual = $sut->created($data);

        $this->assertSame(201, $actual->getStatusCode());
        $content = $this->json->jsonToArray((string)$actual->getContent());
        $this->assertSame($data, $content['data']);
    }
}
