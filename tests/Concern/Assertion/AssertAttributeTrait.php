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
}
