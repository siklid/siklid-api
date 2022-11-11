<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Service\Storage;

use App\Foundation\Service\Storage\LocalStorage;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor - This is a unit test.
 */
class LocalStorageTest extends TestCase
{
    private LocalStorage $sut;

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedAssignment - faker::text() returns a string.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new LocalStorage();
    }

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment - faker::text() returns a string.
     * @psalm-suppress MixedArgument - faker::text() returns a string.
     */
    public function write(): string
    {
        $textContent = $this->faker->text();
        $path = __DIR__.'/test.txt';
        $this->sut->write($path, $textContent, []);

        $this->assertFileExists($path);

        return $textContent;
    }

    /**
     * @test
     *
     * @depends write
     */
    public function read(string $expectedContent): void
    {
        $path = __DIR__.'/test.txt';
        $content = $this->sut->read($path);

        $this->assertSame($expectedContent, $content);
        unlink($path);
    }
}
