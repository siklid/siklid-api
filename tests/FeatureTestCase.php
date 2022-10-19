<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Utils\Json;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * FeatureTestCase is the base class for functional tests.
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FeatureTestCase extends WebTestCase
{
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

    protected function getDocumentManager(): DocumentManager
    {
        /** @var DocumentManager $dm */
        $dm = self::getContainer()->get('doctrine_mongodb.odm.document_manager');

        return $dm;
    }

    protected function assertExists(string $class, array $criteria): void
    {
        $repository = $this->getDocumentManager()->getRepository($class);
        $object = $repository->findOneBy($criteria);
        $this->assertNotNull($object);
    }
}
