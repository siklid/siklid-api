<?php

namespace App\Tests\Unit\Foundation;

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
        /** @psalm-suppress InvalidArgument */
        $sut->setErrorIterator($errorIterator);

        $response = $sut->render();

        $this->assertSame(422, $response->getStatusCode());
        $expectedContent = '{"message":"Invalid request","errors":{"property_path":["Error message"]}}';
        $this->assertSame($expectedContent, $response->getContent());
    }
}
