<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Username;
use App\Foundation\ValueType\UsernameType;
use App\Tests\TestCase;

class UsernameTypeTest extends TestCase
{
    /**
     * @test
     */
    public function convert_to_db_value_accepts_only_value_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(UsernameType::class);

        $sut->convertToDatabaseValue('foo');
    }

    /**
     * @test
     */
    public function null_value_should_be_skipped_by_doctrine(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(UsernameType::class);

        $sut->convertToPHPValue(null);
    }

    /**
     * @test
     */
    public function convert_to_db_value(): void
    {
        $sut = $this->createMongoType(UsernameType::class);
        $username = Username::fromString('foo');

        $actual = $sut->convertToDatabaseValue($username);

        $this->assertSame('foo', $actual);
    }

    /**
     * @test
     */
    public function convert_to_php_value(): void
    {
        $sut = $this->createMongoType(UsernameType::class);

        $actual = $sut->convertToPHPValue('foo');

        $this->assertTrue($actual->equals(Username::fromString('foo')));
    }
}
