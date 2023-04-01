<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation;

use App\Foundation\Exception\ValidationException;
use App\Foundation\Validation\Validator;
use App\Foundation\Validation\ValidatorInterface;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;

class ValidatorTest extends TestCase
{
    use KernelTestCaseTrait;

    private ValidatorInterface $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $validator = $this->container()->get(SymfonyValidator::class);
        $this->sut = new Validator($validator);
    }

    /**
     * @test
     */
    public function stop_unless_valid_with_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $this->sut->stopUnlessValid(new Foo());
    }

    /**
     * @test
     */
    public function stop_unless_valid_with_valid_data(): void
    {
        $this->expectNotToPerformAssertions();

        $this->sut->stopUnlessValid(new Foo('foo'));
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
