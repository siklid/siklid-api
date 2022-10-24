<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\ValueObject;

use App\Foundation\ValueObject\Slug;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SlugTest extends TestCase
{
    /**
     * @test
     */
    public function from_string(): void
    {
        $invalid = '<[my-slug';

        $slug = Slug::slugify($invalid);

        $this->assertSame('my-slug', (string)$slug);
    }

    /**
     * @test
     */
    public function to_string(): void
    {
        $slug = Slug::fromString('my-slug');

        $this->assertSame('my-slug', (string)$slug);
    }

    /**
     * @test
     */
    public function equals(): void
    {
        $sut = Slug::fromString('my-slug');
        $other = Slug::fromString('my-slug');

        $this->assertTrue($sut->equals($other));
    }
}
