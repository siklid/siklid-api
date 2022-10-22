<?php

declare(strict_types=1);

namespace App\Tests\Integration\Http;

use App\Foundation\Http\ApiController;
use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class ApiControllerTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function created(): void
    {
        $sut = new class() extends ApiController {
            public function __invoke(): void
            {
                $this->created('foo');
            }
        };
        $sut->setContainer($this->container());

        $response = $sut->created('foo', ['bar']);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('{"data":"foo"}', $response->getContent());
    }
}
