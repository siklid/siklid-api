<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Validator;

use App\Foundation\Constraint\Slug as ConstraintSlug;
use App\Foundation\Validator\SlugValidator;
use App\Foundation\ValueObject\Slug;
use App\Tests\TestCase;
use stdClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @psalm-suppress MissingConstructor - We don't need a constructor
 */
class SlugValidatorTest extends TestCase
{
    private SlugValidator $sut;
    private ExecutionContext $context;
    private ConstraintSlug $constraint;

    /**
     * @psalm-suppress InternalClass
     * @psalm-suppress InternalMethod
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new SlugValidator();
        $this->context = new ExecutionContext(
            $this->createMock(ValidatorInterface::class),
            'slug',
            $this->createMock(TranslatorInterface::class)
        );
        $this->sut->initialize($this->context);
        $this->constraint = $this->createMock(ConstraintSlug::class);
        $this->constraint->method('message')->willReturn('The string "{{ string }}" is not a valid slug.');
    }

    /**
     * @test
     */
    public function constraint_should_be_slug_constraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $constraint = $this->getMockForAbstractClass(Constraint::class);

        $this->sut->validate('foo', $constraint);
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function validate_should_not_validate_empty_value(): void
    {
        $this->sut->validate('', $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function validate_should_not_validate_null_value(): void
    {
        $this->sut->validate(null, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }

    /**
     * @test
     */
    public function it_should_not_validate_std_object(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->sut->validate(new stdClass(), $this->constraint);
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_validate_string(): void
    {
        $invalidSlug = 'foo bar';

        $this->sut->validate($invalidSlug, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertSame('The string "{{ string }}" is not a valid slug.', $violation->getMessageTemplate());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_validate_slug_value_objects(): void
    {
        $invalidSlug = Slug::fromString('foo bar');

        $this->sut->validate($invalidSlug, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertSame('The string "{{ string }}" is not a valid slug.', $violation->getMessageTemplate());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_pass_a_valid_slug_string(): void
    {
        $validSlug = 'foo-bar';

        $this->sut->validate($validSlug, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_pass_a_valid_slug_value_object(): void
    {
        $validSlug = Slug::fromString('foo-bar');

        $this->sut->validate($validSlug, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }
}
