<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Action;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Action\ValidatableInterface;
use App\Foundation\Exception\ValidationException;
use App\Tests\TestCase;
use Symfony\Component\Form\FormInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class AbstractionActionTest extends TestCase
{
    private ValidatableInterface $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(ValidatableInterface::class);
    }

    /**
     * @test
     */
    public function validate_throws_validation_exception_if_data_is_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('isValid')->willReturn(false);

        $sut = $this->getMockForAbstractClass(AbstractAction::class);

        $sut->validate($form, $this->request);
    }

    /**
     * @test
     */
    public function validate_returns_void_if_data_is_valid(): void
    {
        $this->expectNotToPerformAssertions();

        $form = $this->createMock(FormInterface::class);
        $form->method('isValid')->willReturn(true);

        $sut = $this->getMockForAbstractClass(AbstractAction::class);

        $sut->validate($form, $this->request);
    }
}
