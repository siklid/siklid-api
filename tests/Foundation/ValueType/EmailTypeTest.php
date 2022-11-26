<?php

declare(strict_types=1);

namespace App\Tests\Foundation\ValueType;

use App\Foundation\Exception\InvalidArgumentException;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueType\EmailType;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;

class EmailTypeTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function convert_to_database_value(): void
    {
        $sut = $this->createMongoType(EmailType::class);
        $email = $this->faker->email();

        $actual = $sut->convertToDatabaseValue(Email::fromString($email));

        $this->assertSame($email, $actual);
    }

    /**
     * @test
     */
    public function convert_to_database_value_accepts_string_email_value(): void
    {
        $sut = $this->createMongoType(EmailType::class);
        $value = $this->faker->email();

        $sut->convertToDatabaseValue($value);

        $this->assertSame($value, $sut->convertToDatabaseValue($value));
    }

    /**
     * @test
     */
    public function null_value_should_be_skipped_by_doctrine(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $this->createMongoType(EmailType::class);

        $sut->convertToPHPValue(null);
    }

    /**
     * @test
     */
    public function convert_to_php_value(): void
    {
        $sut = $this->createMongoType(EmailType::class);
        $email = $this->faker->email();

        $actual = $sut->convertToPHPValue($email);

        $this->assertTrue($actual->equals(Email::fromString($email)));
    }
}
