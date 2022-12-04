<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Tests\Concern\Assertion\AssertODMTrait;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\Repository\GridFSRepository;
use Doctrine\ODM\MongoDB\Repository\ViewRepository;
use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * This trait is used to test features that require a kernel.
 * It should replace the KernelTestCase class.
 *
 * @mixin TestCase
 */
trait KernelTestCaseTrait
{
    use MailerAssertionsTrait;
    use AssertODMTrait;

    protected static ?string $class = null;

    protected static ?KernelInterface $kernel = null;

    protected static bool $booted = false;

    protected array $touchedCollections = [];

    /**
     * Custom template method to tear down the test case.
     *
     * @used-by TestCase::tearDown()
     */
    protected function tearDownKernel(): void
    {
        $this->dropTouchedCollections();
        static::ensureKernelShutdown();
        static::$class = null;
        static::$kernel = null;
        static::$booted = false;
    }

    /**
     * @throws RuntimeException
     * @throws LogicException
     */
    protected static function getKernelClass(): string
    {
        if (! isset($_SERVER['KERNEL_CLASS']) && ! isset($_ENV['KERNEL_CLASS'])) {
            throw new LogicException(sprintf('You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the "%1$s::createKernel()" or "%1$s::getKernelClass()" method.', static::class));
        }

        if (! class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new RuntimeException(sprintf('Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the "%s::createKernel()" method.', $class, static::class));
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
            throw new LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
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

        $debug = '1' === $debug ? true : $debug;

        return new static::$class($env, $debug);
    }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     */
    protected static function ensureKernelShutdown(): void
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
     * @param Application    $application The console application
     * @param string|Command $command     The command to test
     */
    protected function cmdTester(Application $application, string|Command $command): CommandTester
    {
        $command = $command instanceof Command ? $command : $application->find($command);

        return new CommandTester($command);
    }

    /**
     * Get document manager instance.
     */
    protected function getDocumentManager(): DocumentManager
    {
        /** @var DocumentManager $dm */
        $dm = self::getContainer()->get(DocumentManager::class);

        return $dm;
    }

    /**
     * Gets the repository for a document class.
     *
     * @param class-string $className the name of the Document
     *
     * @return DocumentRepository<T>|GridFSRepository<T>|ViewRepository<T> the repository
     *
     * @template T of object
     */
    protected function getRepository(string $className): DocumentRepository|ViewRepository|GridFSRepository
    {
        return $this->getDocumentManager()->getRepository($className);
    }

    /**
     * Deletes the given document from the database.
     */
    protected function deleteDocument(string|object $class, array $criteria = []): void
    {
        if (is_object($class)) {
            $this->getDocumentManager()->remove($class);
            $this->getDocumentManager()->flush();

            return;
        }

        $repository = $this->getRepository($class);
        $object = $repository->findOneBy($criteria);

        $this->getDocumentManager()->remove($object);
        $this->getDocumentManager()->flush();
    }

    /**
     * Drops the collection of the given class.
     */
    protected function dropCollection(string $class): void
    {
        $collection = $this->getDocumentManager()->getDocumentCollection($class);
        $collection->drop();
        $this->getDocumentManager()->clear();
    }

    /**
     * Persists the given document to the database.
     */
    protected function persistDocument(object $document): void
    {
        $this->getDocumentManager()->persist($document);
        $this->getDocumentManager()->flush();
    }

    /**
     * Adds the given document to the touched collections.
     *
     * @param class-string $class The document class
     */
    protected function touchCollection(string $class): void
    {
        if (! in_array($class, $this->touchedCollections, true)) {
            $this->touchedCollections[] = $class;
        }
    }

    /**
     * drops all touched collections.
     * This is useful to avoid side effects between tests.
     */
    protected function dropTouchedCollections(): void
    {
        foreach ($this->touchedCollections as $class) {
            $this->dropCollection($class);
        }
    }

    /**
     * Returns the value of the given parameter.
     *
     * @return mixed The value of the parameter
     *
     * @psalm-suppress PossiblyUndefinedMethod - The user should know about the config values
     * @psalm-suppress MixedAssignment - Expected to be mixed
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        $keyParts = explode('.', $key);
        $config = $this->container()->getParameter($keyParts[0]);

        array_shift($keyParts);

        foreach ($keyParts as $keyPart) {
            if (! isset($config[$keyPart])) {
                return $default;
            }

            $config = $config[$keyPart];
        }

        return $config ?? $default;
    }
}
