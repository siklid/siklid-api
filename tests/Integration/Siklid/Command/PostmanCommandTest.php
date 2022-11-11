<?php

declare(strict_types=1);

namespace App\Tests\Integration\Siklid\Command;

use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor for this class
 */
class PostmanCommandTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function it_generates_a_json_collection_from_yaml(): void
    {
        $sut = $this->cmdTester($this->consoleApplication(), 'siklid:postman');

        $sut->execute([]);

        $sut->assertCommandIsSuccessful();
    }
}
