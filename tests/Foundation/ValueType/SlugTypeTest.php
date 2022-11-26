<?php

declare(strict_types=1);

namespace App\Tests\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Slug;
use App\Foundation\ValueType\SlugType;
use App\Tests\TestCase;

class SlugTypeTest extends TestCase
{
    /**
     * @test
     */
    public function convert_to_database_value(): void
    {
        $sut = $this->createMongoType(SlugType::class);

        $this->assertSame('foo', $sut->convertToDatabaseValue(Slug::fromString('foo')));
    }

    /**
     * @test
     */
    public function convert_to_db_value_process_only_slug_value_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(SlugType::class);

        $sut->convertToDatabaseValue('foo');
    }

    /**
     * @test
     */
    public function convert_to_php_value(): void
    {
        $sut = $this->createMongoType(SlugType::class);

        $this->assertEquals(Slug::fromString('foo'), $sut->convertToPHPValue('foo'));
    }

    /**
     * @test
     */
    public function it_should_not_convert_null_db_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(SlugType::class);

        $sut->convertToPHPValue(null);
    }
}
