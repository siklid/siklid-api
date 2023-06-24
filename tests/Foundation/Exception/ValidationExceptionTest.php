<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Exception;

use App\Foundation\Exception\ValidationException;
use App\Tests\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function render_with_violation_list(): void
    {
        $sut = new ValidationException();
        $violation = new ConstraintViolation(
            'Error message',
            'Error message',
            [],
            'root',
            'property_path',
            'invalid_value'
        );
        $violationList = new ConstraintViolationList([$violation]);
        $sut->setViolationList($violationList);

        $response = $sut->render();

        $this->assertSame(400, $response->getStatusCode());
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

        $this->assertSame(400, $response->getStatusCode());
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

        $this->assertSame(400, $response->getStatusCode());
        $expectedContent = '{"message":"Custom message","errors":[]}';
        $this->assertSame($expectedContent, $response->getContent());
    }
}
