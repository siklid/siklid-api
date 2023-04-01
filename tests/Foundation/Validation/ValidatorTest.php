<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use App\Foundation\Validation\Validator;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorTest extends TestCase
{
    use KernelTestCaseTrait;

    /**
     * @test
     */
    public function stop_unless_valid_with_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $validator = $this->container()->get(ValidatorInterface::class);
        $sut = new Validator($validator);

        $sut->stopUnlessValid(new Foo());
    }

    /**
     * @test
     */
    public function stop_unless_valid_with_valid_data(): void
    {
        $validator = $this->container()->get(ValidatorInterface::class);
        $sut = new Validator($validator);

        $violations = $sut->stopUnlessValid(new Foo('foo'));
        $this->assertCount(0, $violations);
    }
}

class Foo
{
    #[Assert\NotBlank]
    public ?string $id = null;

    public function __construct(?string $id = null)
    {
        $this->id = $id;
    }
}
