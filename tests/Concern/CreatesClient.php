<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\Concern\Util\WithJson;
use App\Tests\TestCase;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * This trait is used to test features that require a client.
 * It should replace the WebTestCase class.
 *
 * @mixin KernelTestCase
 */
trait CreatesClient
{
    use CreatesKernel;
    use WebTestAssertionsTrait;
    use WithFaker;
    use WithJson;
    use UserFactoryTrait;

    /**
     * Custom template method to tear down the test case.
     *
     * @used-by TestCase::tearDown()
     */
    protected function tearDownClient(): void
    {
        self::getClient(null);
    }

    /**
     * Creates a KernelBrowser.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (static::$booted) {
            throw new LogicException(sprintf('Booting the kernel before calling "%s()" is not supported, the kernel should only be booted once.', __METHOD__));
        }

        $kernel = static::bootKernel($options);

        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new LogicException('You cannot create the client used in functional tests if the "framework.test" config is not set to true.');
            }
            throw new LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".');
        }

        $client->setServerParameters($server);

        $kernelBrowser = self::getClient($client);
        assert($kernelBrowser instanceof KernelBrowser);

        return $kernelBrowser;
    }

    /**
     * A wrapper for the static createClient() method.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server  An array of server parameters
     */
    protected function createCrawler(array $options = [], array $server = []): KernelBrowser
    {
        return self::createClient($options, $server);
    }

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
        $this->assertResponseIsunprocessableEntity($message);
    }

    /**
     * Asserts that the response status code is 422.
     */
    protected function assertResponseIsunprocessableEntity(string $message = ''): void
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

    protected function assertResponseJsonStructure(KernelBrowser $client, array $structure): void
    {
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

    /**
     * Get response or a part of it.
     *
     * @psalm-suppress MixedReturnStatement
     */
    protected function getFromResponse(KernelBrowser $client, ?string $key = null): mixed
    {
        $json = (string)$client->getResponse()->getContent();
        $content = $this->json->jsonToArray($json);

        if (null === $key) {
            return $content;
        }

        $keyParts = explode('.', $key);
        foreach ($keyParts as $iValue) {
            $content = $content[$iValue];
        }

        return $content;
    }
}
