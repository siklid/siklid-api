<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation\Constraint;

use App\Foundation\Validation\Constraint\Exists;
use App\Foundation\Validation\Constraint\ExistsValidator;
use App\Tests\TestCase;
use Attribute;

class ExistsTest extends TestCase
{
    /**
     * @test
     */
    public function is_attribute(): void
    {
        $sut = new Exists();

        $this->assertHasAttribute($sut, Attribute::class);
    }

    /**
     * @test
     */
    public function validated_by(): void
    {
        $sut = new Exists();

        $this->assertSame(ExistsValidator::class, $sut->validatedBy());
    }

    /**
     * @test
     */
    public function message(): void
    {
        $sut = new Exists();

        $this->assertSame('The value "{{ value }}" does not exist.', $sut->message());
    }
}
