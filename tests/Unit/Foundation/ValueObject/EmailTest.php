<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\ValueObject;

use App\Foundation\Exception\ValidationException;
use App\Foundation\ValueObject\Email;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;
use Assert\AssertionFailedException;
use JsonException;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor.
 */
class EmailTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     *
     * @throws AssertionFailedException
     */
    public function validate(): void
    {
        $this->expectException(ValidationException::class);

        $email = Email::fromString('invalid-email');
        $email->validate();
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

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment - $value is a string
     * @psalm-suppress MixedArgument - $value is a string
     *
     * @throws JsonException
     */
    public function json_serialize(): void
    {
        $value = $this->faker->email();
        $sut = Email::fromString($value);

        $this->assertSame('"'.$value.'"', json_encode($sut, JSON_THROW_ON_ERROR));
    }
}
