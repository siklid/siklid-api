<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation\Constraint;

use App\Foundation\Validation\Constraint\Username;
use App\Foundation\Validation\Constraint\UsernameValidator;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class UsernameTest extends TestCase
{
    private Username $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new Username();
    }

    /**
     * @test
     */
    public function message(): void
    {
        $this->assertSame('The string "{{ string }}" is not a valid username.', $this->sut->message());
    }

    /**
     * @test
     */
    public function validated_by(): void
    {
        $this->assertSame(UsernameValidator::class, $this->sut->validatedBy());
    }
}
