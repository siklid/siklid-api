<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Pagination;

use App\Foundation\Pagination\Page;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class PageTest extends TestCase
{
    /**
     * @test
     */
    public function get_data(): void
    {
        $sut = new Page();
        $sut->data(['foo' => 'bar']);

        $actual = $sut->getData();

        $this->assertSame(['foo' => 'bar'], $actual);
    }

    /**
     * @test
     */
    public function get_meta(): void
    {
        $sut = new Page();
        $sut->meta(['count' => 99]);

        $actual = $sut->getMeta();

        $this->assertSame(['count' => 99], $actual);
    }

    /**
     * @test
     */
    public function get_links(): void
    {
        $sut = new Page();
        $sut->links(['self' => 'https://example.com']);

        $actual = $sut->getLinks();

        $this->assertSame(['self' => 'https://example.com'], $actual);
    }

    /**
     * @test
     */
    public function json_serialize(): void
    {
        $sut = Page::init()->data(['foo' => 'bar'])->meta(['count' => 99])->links(['self' => 'https://example.com']);

        $actual = $sut->jsonSerialize();

        $this->assertSame(
            ['data' => ['foo' => 'bar'], 'meta' => ['count' => 99], 'links' => ['self' => 'https://example.com']],
            $actual
        );
    }
}
