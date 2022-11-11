<?php

declare(strict_types=1);

namespace App\Tests\Integration\Siklid\Command;

use App\Foundation\Service\Storage\LocalStorage;
use App\Foundation\Service\Storage\StorageInterface;
use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor for this class
 */
class PostmanCommandTest extends IntegrationTestCase
{
    private StorageInterface $storage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = new LocalStorage();
    }

    /**
     * @test
     */
    public function it_generates_a_json_collection_from_yaml(): void
    {
        $postmanConfig = (array)$this->container()->getParameter('postman');
        $dist = (string)$postmanConfig['dist'];
        if (file_exists($dist)) {
            unlink($dist);
        }

        $sut = $this->cmdTester($this->consoleApplication(), 'siklid:postman');

        $sut->execute([]);

        $sut->assertCommandIsSuccessful();
        $this->assertFileExists($dist);
        $content = $this->json->jsonToArray($this->storage->read($dist));
        foreach (['info', 'variable', 'item'] as $key) {
            $this->assertArrayHasKey($key, $content);
        }
    }
}
