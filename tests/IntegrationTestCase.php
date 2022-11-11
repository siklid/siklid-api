<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * IntegrationTestCase is the base class for integration tests.
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress PossiblyNullArgument
 */
class IntegrationTestCase extends KernelTestCase
{
    use DBTrait;

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
     * It's a wrapper around the static::getContainer() method.
     */
    protected function container(): ContainerInterface
    {
        return static::getContainer();
    }

    /**
     * Creates a dedicated console application.
     *
     * @param array $options The kernel options
     */
    protected function consoleApplication(array $options = []): Application
    {
        $kernel = self::bootKernel($options);

        return new Application($kernel);
    }

    /**
     * Creates a command tester.
     *
     * @param Application    $application The console application
     * @param string|Command $command     The command to test
     */
    protected function cmdTester(Application $application, string|Command $command): CommandTester
    {
        $command = $command instanceof Command ? $command : $application->find($command);

        return new CommandTester($command);
    }
}
