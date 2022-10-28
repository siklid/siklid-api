<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Exception;

use App\Foundation\Exception\ValidationException;
use App\Tests\TestCase;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ValidationExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @psalm-suppress InvalidArgument - We know the type of ErrorIterator
     */
    public function render(): void
    {
        $sut = new ValidationException();
        $formError = new FormError(
            'Error message',
            'Error message',
            [],
            null,
            new ConstraintViolation('Error message', 'Error message', [], 'root', 'property_path', 'invalid_value')
        );

        $errorIterator = new FormErrorIterator(
            $this->createMock(FormInterface::class),
            [$formError]
        );
        $sut->setErrorIterator($errorIterator);

        $response = $sut->render();

        $this->assertSame(422, $response->getStatusCode());
        $expectedContent = '{"message":"Invalid request","errors":{"property_path":["Error message"]}}';
        $this->assertSame($expectedContent, $response->getContent());
    }

    /**
     * @test
     */
    public function render_returns_empty_array_if_error_iterator_is_null(): void
    {
        $sut = new ValidationException();

        $response = $sut->render();

        $this->assertSame(422, $response->getStatusCode());
        $expectedContent = '{"message":"Invalid request","errors":[]}';
        $this->assertSame($expectedContent, $response->getContent());
    }

    /**
     * @test
     */
    public function render_gets_message_from_the_exception_message(): void
    {
        $sut = new ValidationException('Custom message');

        $response = $sut->render();

        $this->assertSame(422, $response->getStatusCode());
        $expectedContent = '{"message":"Custom message","errors":[]}';
        $this->assertSame($expectedContent, $response->getContent());
    }
}
