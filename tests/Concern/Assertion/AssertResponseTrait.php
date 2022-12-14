<?php

declare(strict_types=1);

namespace App\Tests\Concern\Assertion;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * This trait is used to assert responses.
 *
 * @mixin KernelTestCase
 */
trait AssertResponseTrait
{
    /**
     * Asserts that the response status code is 200.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsOk(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_OK, $message);
    }

    /**
     * Asserts that the response status code is 201.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsCreated(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED, $message);
    }

    /**
     * Asserts that the response status code is 404.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsNotFound(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND, $message);
    }

    /**
     * Asserts that the response status code is 400.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsBadRequest(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST, $message);
    }

    /**
     * Asserts that the response status code is 403.
     */
    protected function assertResponseIsForbidden(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN, $message);
    }

    /**
     * Assert that the response has validation errors.
     */
    protected function assertResponseHasValidationError(string $message = ''): void
    {
        $this->assertResponseIsUnprocessableEntity($message);
    }

    /**
     * Asserts that the response status code is 422.
     */
    protected function assertResponseIsUnprocessableEntity(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY, $message);
    }

    /**
     * Asserts that response format is JSON.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsJson(string $message = ''): void
    {
        self::assertResponseFormatSame('json', $message);
    }

    protected function assertResponseJsonStructure(array $structure): void
    {
        $client = self::getClient();
        $json = (string)$client->getResponse()->getContent();
        $content = $this->json->jsonToArray($json);

        $this->assertStructure($content, $structure);
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgument
     */
    protected function assertStructure(array $content, array $structure): void
    {
        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $this->assertArrayHasKey($key, $content);
                $this->assertStructure($content[$key], $value);
            } else {
                $this->assertArrayHasKey($value, $content);
            }
        }
    }
}
