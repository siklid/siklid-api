<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Http;

use App\Foundation\Http\ApiController;
use App\Foundation\Pagination\Page;
use App\Tests\Concern\Util\WithJson;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @psalm-suppress MissingConstructor
 */
class ApiControllerTest extends TestCase
{
    use WithJson;

    private ApiController $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->getMockForAbstractClass(ApiController::class);
        $this->sut->setContainer($this->createMock(ContainerInterface::class));
    }

    /**
     * @test
     *
     * @dataProvider provideResponsesForOk
     */
    public function ok(mixed $data, array $expected): void
    {
        $actual = $this->sut->ok($data);
        $this->assertSame(Response::HTTP_OK, $actual->getStatusCode());
        $content = $this->json->jsonToArray((string)$actual->getContent());
        $this->assertSame($content, $expected);
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

    /**
     * @return array[]
     */
    public function provideResponsesForOk(): array
    {
        return [
            'null' => [null, ['data' => null]],
            'array' => [['foo' => 'bar'], ['data' => ['foo' => 'bar']]],
            'page' => [Page::init()->data(['foo' => 'bar']), ['data' => ['foo' => 'bar'], 'meta' => [], 'links' => []]],
        ];
    }
}
