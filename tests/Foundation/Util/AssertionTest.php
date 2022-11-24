<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Util;

use App\Foundation\Exception\ValidationException;
use App\Foundation\Util\Assert;
use App\Tests\TestCase;
use Assert\Assertion;
use ReflectionClass;

class AssertionTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_validation_exception_if_assertion_failed(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid email address');

        Assert::email('invalid-email', 'Invalid email address');
    }

    /**
     * @test
     *
     * @psalm-suppress MixedAssignment - ReflectionClass is not supported by psalm
     */
    public function it_provides_same_constants_as_the_assertion_library(): void
    {
        $reflection = new ReflectionClass(Assertion::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $name => $value) {
            $this->assertSame($value, constant(Assert::class.'::'.$name));
        }
    }
}
