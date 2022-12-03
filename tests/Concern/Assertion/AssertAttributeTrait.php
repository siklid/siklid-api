<?php

declare(strict_types=1);

namespace App\Tests\Concern\Assertion;

use App\Tests\TestCase;
use ReflectionClass;

/**
 * @mixin TestCase
 */
trait AssertAttributeTrait
{
    /**
     * Asserts an attribute is present on the given object.
     */
    protected function assertHasAttribute(object $sut, string $attribute, string $message = ''): void
    {
        $reflection = new ReflectionClass($sut);
        $classAttributes = $reflection->getAttributes();
        $attributeNames = array_map(static fn ($attribute) => $attribute->getName(), $classAttributes);
        $this->assertContains($attribute, $attributeNames, $message);
    }

    /**
     * Asserts a class method has an attribute.
     */
    protected function assertMethodHasAttribute(
        object $sut,
        string $method,
        string $attribute,
        string $message = ''
    ): void {
        $reflection = new ReflectionClass($sut);
        $method = $reflection->getMethod($method);
        $methodAttributes = $method->getAttributes();
        $attributeNames = array_map(static fn ($attribute) => $attribute->getName(), $methodAttributes);
        $this->assertContains($attribute, $attributeNames, $message);
    }
}
