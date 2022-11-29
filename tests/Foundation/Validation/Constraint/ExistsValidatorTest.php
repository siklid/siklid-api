<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation\Constraint;

use App\Foundation\Validation\Constraint\Exists;
use App\Foundation\Validation\Constraint\ExistsValidator;
use App\Siklid\Document\User;
use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ExistsValidatorTest extends TestCase
{
    use KernelTestCaseTrait;
    use UserFactoryTrait;

    private ExecutionContext $context;

    private ExistsValidator $sut;

    /**
     * @psalm-suppress InternalClass
     * @psalm-suppress InternalMethod
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ExistsValidator($this->getDocumentManager());
        $this->context = new ExecutionContext(
            $this->createMock(ValidatorInterface::class),
            'root',
            $this->createMock(TranslatorInterface::class)
        );
        $this->sut->initialize($this->context);
    }

    /**
     * @test
     */
    public function it_validates_exists_constraint(): void
    {
        $constraint = new class() extends Constraint {
        };

        $this->expectException(UnexpectedTypeException::class);

        $this->sut->validate('foo', $constraint);
    }

    /**
     * @test
     */
    public function it_skips_empty_values(): void
    {
        $this->expectNotToPerformAssertions();

        $this->sut->validate(null, new Exists());
        $this->sut->validate('', new Exists());
    }

    /**
     * @test
     */
    public function it_requires_document(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Document class must be specified.');

        $this->sut->validate('foo', new Exists());
    }

    /**
     * @test
     */
    public function it_requires_field(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Field must be specified.');

        $this->sut->validate('foo', new Exists('foo', ''));
    }

    /**
     * @test
     */
    public function document_class_should_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Document class "Foo" does not exist.');

        $this->sut->validate('foo', new Exists('Foo'));
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_adds_violation_if_not_exists(): void
    {
        $this->sut->validate('bar', new Exists(User::class, 'email'));

        $this->assertCount(1, $this->context->getViolations());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_passes_if_data_exists(): void
    {
        $user = $this->makeUser();
        $this->persistDocument($user);

        // > Act start (different valid values)
        $this->sut->validate($user->getId(), new Exists(User::class));
        $this->sut->validate($user->getId(), new Exists(User::class, 'id'));
        $this->sut->validate($user->getUsername(), new Exists(User::class, 'username'));
        // < Act end

        $this->assertCount(0, $this->context->getViolations());
    }

    /**
     * @test
     *
     * @psalm-suppress InternalMethod
     */
    public function it_passes_if_document_exists(): void
    {
        $user = $this->makeUser();
        $this->persistDocument($user);

        $this->sut->validate($user, new Exists(User::class));

        $this->assertCount(0, $this->context->getViolations());
    }
}
