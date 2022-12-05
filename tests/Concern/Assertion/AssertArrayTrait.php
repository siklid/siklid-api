<?php

declare(strict_types=1);

namespace App\Tests\Concern\Assertion;

use App\Tests\TestCase;

/**
 * @mixin TestCase
 */
trait AssertArrayTrait
{
    /**
     * Asserts that two arrays have the same keys and values.
     */
    protected function assertArrayEquals(array $expected, array $actual): void
    {
        $this->assertSameSize($expected, $actual);
        $this->assertEmpty(array_diff($expected, $actual));
    }
}
