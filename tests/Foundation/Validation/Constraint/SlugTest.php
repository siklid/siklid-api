<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation\Constraint;

use App\Foundation\Validation\Constraint\Slug;
use App\Foundation\Validation\Constraint\SlugValidator;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SlugTest extends TestCase
{
    private Slug $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new Slug();
    }

    /**
     * @test
     */
    public function message(): void
    {
        $this->assertSame('The string "{{ string }}" is not a valid slug.', $this->sut->message());
    }

    /**
     * @test
     */
    public function validated_by(): void
    {
        $this->assertSame(SlugValidator::class, $this->sut->validatedBy());
    }
}
