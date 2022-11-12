<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;
use App\Tests\Concerns\DBTrait;
use App\Tests\Concerns\UserFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FeatureTestCase is the base class for functional tests.
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FeatureTestCase extends WebTestCase
{
    use DBTrait;
    use UserFactoryTrait;

    protected Faker $faker;

    protected Json $json;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->json = new Json();
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
        self::assertResponseStatusCodeSame(200, $message);
    }

    /**
     * Asserts that the response status code is 201.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsCreated(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(201, $message);
    }

    /**
     * Asserts that the response status code is 404.
     *
     * @param string $message Assertion message
     */
    protected function assertResponseIsNotFound(string $message = ''): void
    {
        self::assertResponseStatusCodeSame(404, $message);
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
            if (is_int($key)) {
                $this->assertArrayHasKey($value, $content);
            } else {
                $this->assertArrayHasKey($key, $content);
                $this->assertStructure($content[$key], $value);
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

    /**
     * Returns the container instance.
     */
    protected function container(): ContainerInterface
    {
        return self::getContainer();
    }
}
