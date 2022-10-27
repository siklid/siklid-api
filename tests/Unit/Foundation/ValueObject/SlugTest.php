<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\ValueObject;

use App\Foundation\ValueObject\Slug;
use App\Tests\TestCase;
use Error;
use JsonException;

/**
 * @psalm-suppress MissingConstructor
 */
class SlugTest extends TestCase
{
    /**
     * @test
     *
     * @psalm-suppress InaccessibleMethod
     */
    public function it_is_not_instantiable_by_public_constructor(): void
    {
        $this->expectException(Error::class);

        // This way prevents IDEs from complaining about the constructor being private.
        $sut = Slug::class;
        new $sut('foo', 'foo');
    }

    /**
     * @test
     */
    public function from_string(): void
    {
        $invalid = '<[my-slug';

        $slug = Slug::fromString($invalid);

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
     *
     * @throws JsonException
     */
    public function json_serialize(): void
    {
        $sut = Slug::fromString('my-slug');

        $this->assertSame('"my-slug"', json_encode($sut, JSON_THROW_ON_ERROR));
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

    /**
     * @test
     */
    public function it_should_preserve_the_original_value(): void
    {
        $original = 'My Slug';

        $sut = Slug::fromString($original);

        $this->assertSame($original, $sut->original());
    }
}
