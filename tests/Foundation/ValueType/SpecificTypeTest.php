<?php

declare(strict_types=1);

namespace App\Tests\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueType\CoercibleEnum;
use App\Foundation\ValueType\SpecificType;
use App\Tests\TestCase;

class SpecificTypeTest extends TestCase
{
    /**
     * @test
     */
    public function convert_to_db_value(): void
    {
        $sut = $this->createMongoType(SpecificType::class);
        $coercibleEnum = MyCoercibleEnum::coerce('foo');

        $actual = $sut->convertToDatabaseValue($coercibleEnum);

        $this->assertSame(['name' => MyCoercibleEnum::class, 'value' => 'foo'], $actual);
    }

    /**
     * @test
     */
    public function convert_to_db_value_accepts_only_coercible_enum(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(SpecificType::class);
        $nonCoercibleEnum = NonCoercibleEnum::from('foo');

        $sut->convertToDatabaseValue($nonCoercibleEnum);
    }

    /**
     * @test
     */
    public function convert_to_php_value(): void
    {
        $arr = ['name' => MyCoercibleEnum::class, 'value' => 'foo'];
        $sut = $this->createMongoType(SpecificType::class);

        $actual = $sut->convertToPHPValue($arr);

        $this->assertInstanceOf(MyCoercibleEnum::class, $actual);
        $this->assertSame('foo', $actual->value);
    }

    /**
     * @test
     */
    public function value_key_is_required_to_convert_into_php_value(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $arr = ['name' => MyCoercibleEnum::class];
        $sut = $this->createMongoType(SpecificType::class);

        $sut->convertToPHPValue($arr);
    }

    /**
     * @test
     */
    public function name_key_is_required_to_convert_into_php_value(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $arr = ['value' => 'foo'];
        $sut = $this->createMongoType(SpecificType::class);

        $sut->convertToPHPValue($arr);
    }
}

enum MyCoercibleEnum: string implements CoercibleEnum
{
    case FOO = 'foo';

    public static function coerce(mixed $value): CoercibleEnum
    {
        if ($value instanceof self) {
            return $value;
        }

        return self::from((string)$value);
    }

    public function toArray(): array
    {
        return [
            'name' => self::class,
            'value' => $this->value,
        ];
    }
}

enum NonCoercibleEnum: string
{
    case FOO = 'foo';
    case BAR = 'bar';
}
