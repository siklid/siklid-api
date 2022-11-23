<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;

trait CreatesKernel
{
    use DBTrait;
    use MailerAssertionsTrait;

    protected static $class;

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    protected static $booted = false;

    protected function tearDown(): void
    {
        static::ensureKernelShutdown();
        static::$class = null;
        static::$kernel = null;
        static::$booted = false;
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     */
    protected static function getKernelClass(): string
    {
        if (! isset($_SERVER['KERNEL_CLASS']) && ! isset($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException(
                sprintf(
                    'You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the "%1$s::createKernel()" or "%1$s::getKernelClass()" method.',
                    static::class
                )
            );
        }

        if (! class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new \RuntimeException(
                sprintf(
                    'Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the "%s::createKernel()" method.',
                    $class,
                    static::class
                )
            );
        }

        return $class;
    }

    /**
     * Boots the Kernel for this test.
     */
    protected static function bootKernel(array $options = []): KernelInterface
    {
        static::ensureKernelShutdown();

        $kernel = static::createKernel($options);
        $kernel->boot();
        static::$kernel = $kernel;
        static::$booted = true;

        return static::$kernel;
    }

    /**
     * Provides a dedicated test container with access to both public and private
     * services. The container will not include private services that have been
     * inlined or removed. Private services will be removed when they are not
     * used by other services.
     *
     * Using this method is the best way to get a container from your test code.
     *
     * @return Container
     */
    protected static function getContainer(): ContainerInterface
    {
        if (! static::$booted) {
            static::bootKernel();
        }

        try {
            return self::$kernel->getContainer()->get('test.service_container');
        } catch (ServiceNotFoundException $e) {
            throw new \LogicException(
                'Could not find service "test.service_container". Try updating the "framework.test" config to "true".',
                0,
                $e
            );
        }
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        if (isset($options['environment'])) {
            $env = $options['environment'];
        } elseif (isset($_ENV['APP_ENV'])) {
            $env = $_ENV['APP_ENV'];
        } elseif (isset($_SERVER['APP_ENV'])) {
            $env = $_SERVER['APP_ENV'];
        } else {
            $env = 'test';
        }

        if (isset($options['debug'])) {
            $debug = $options['debug'];
        } elseif (isset($_ENV['APP_DEBUG'])) {
            $debug = $_ENV['APP_DEBUG'];
        } elseif (isset($_SERVER['APP_DEBUG'])) {
            $debug = $_SERVER['APP_DEBUG'];
        } else {
            $debug = true;
        }

        $debug = $debug === "1" ? true : $debug;

        return new static::$class($env, $debug);
    }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     */
    protected static function ensureKernelShutdown()
    {
        if (null !== static::$kernel) {
            static::$kernel->boot();
            $container = static::$kernel->getContainer();
            static::$kernel->shutdown();
            static::$booted = false;

            if ($container instanceof ResetInterface) {
                $container->reset();
            }
        }
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
     * @param Application $application The console application
     * @param string|Command $command  The command to test
     */
    protected function cmdTester(Application $application, string|Command $command): CommandTester
    {
        $command = $command instanceof Command ? $command : $application->find($command);

        return new CommandTester($command);
    }
}
