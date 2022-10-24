<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\ValueObject;

use App\Foundation\Exception\ValidationException;
use App\Foundation\ValueObject\Email;
use App\Tests\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_valid_email(): void
    {
        $this->expectException(ValidationException::class);

        Email::fromString('invalid-email');
    }

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment - $value is a string
     * @psalm-suppress MixedArgument - $value is a string
     */
    public function it_is_stringable(): void
    {
        $value = $this->faker->email();
        $sut = Email::fromString($value);

        $this->assertSame($value, (string)$sut);
        $this->assertEquals($value, $sut);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment - $value is a string
     * @psalm-suppress MixedArgument - $value is a string
     */
    public function equals(): void
    {
        $value = $this->faker->email();
        $sut = Email::fromString($value);
        $other = Email::fromString($value);

        $this->assertTrue($sut->equals($other));
    }
}
