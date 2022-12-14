<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Tests\Concern\Assertion\AssertResponseTrait;
use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\Concern\Util\WithJson;
use App\Tests\TestCase;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * This trait is used to test features that require a client.
 * It should replace the WebTestCase class.
 *
 * @mixin KernelTestCase
 */
trait WebTestCaseTrait
{
    use KernelTestCaseTrait;
    use WebTestAssertionsTrait;
    use WithFaker;
    use WithJson;
    use UserFactoryTrait;
    use AssertResponseTrait;

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
     * @param array $server An array of server parameters
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (static::$booted) {
            throw new LogicException(
                sprintf(
                    'Booting the kernel before calling "%s()" is not supported, the kernel should only be booted once.',
                    __METHOD__
                )
            );
        }

        $kernel = static::bootKernel($options);

        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new LogicException(
                    'You cannot create the client used in functional tests if the "framework.test" config is not set to true.'
                );
            }
            throw new LogicException(
                'You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".'
            );
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
     * @param array $server An array of server parameters
     */
    protected function makeClient(array $options = [], array $server = []): KernelBrowser
    {
        return self::createClient($options, $server);
    }

    /**
     * Get response or a part of it.
     *
     * @psalm-suppress MixedReturnStatement
     */
    protected function getFromResponse(?string $key = null): mixed
    {
        $client = self::getClient();
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
