<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validator;

use App\Foundation\Constraint\Username as ConstraintUsername;
use App\Foundation\Validator\UsernameValidator;
use App\Foundation\ValueObject\Username;
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
class UsernameValidatorTest extends TestCase
{
    private UsernameValidator $sut;

    private ExecutionContext $context;

    private ConstraintUsername $constraint;

    /**
     * @psalm-suppress InternalClass
     * @psalm-suppress InternalMethod
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new UsernameValidator();
        $this->context = new ExecutionContext(
            $this->createMock(ValidatorInterface::class),
            'username',
            $this->createMock(TranslatorInterface::class)
        );
        $this->sut->initialize($this->context);
        $this->constraint = $this->createMock(ConstraintUsername::class);
        $this->constraint->method('message')->willReturn('The string "{{ string }}" is not a valid username.');
    }

    /**
     * @test
     */
    public function constraint_should_be_username_constraint(): void
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
        $invalidUsername = 'foo bar';

        $this->sut->validate($invalidUsername, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertSame($this->constraint->message(), $violation->getMessageTemplate());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_validate_username_value_objects(): void
    {
        $invalidUsername = Username::fromString('foo bar');

        $this->sut->validate($invalidUsername, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(1, $violations);
        $violation = $violations->get(0);
        $this->assertSame($this->constraint->message(), $violation->getMessageTemplate());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_pass_a_valid_username_string(): void
    {
        $validUsername = 'Jayde93';

        $this->sut->validate($validUsername, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_should_pass_a_valid_username_value_object(): void
    {
        $validUsername = Username::fromString('Jayde93');

        $this->sut->validate($validUsername, $this->constraint);

        $violations = $this->context->getViolations();
        $this->assertCount(0, $violations);
    }
}
