<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * IntegrationTestCase is the base class for integration tests.
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class IntegrationTestCase extends KernelTestCase
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
     * Provides a dedicated test container
     * It's a wrapper around the static::getContainer() method
     */
    protected function container(): ContainerInterface
    {
        return static::getContainer();
    }

    /**
     * Creates a dedicated console application
     *
     * @param array $options The kernel options
     *
     * @return Application
     */
    protected function consoleApplication(array $options = []): Application
    {
        $kernel = self::bootKernel($options);

        return new Application($kernel);
    }
}
