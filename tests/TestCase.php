<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ODM\MongoDB\Types\Type;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class TestCase
 * All Unit tests should extend this class.
 *
 * @psalm-suppress MissingConstructor
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTraits();
    }

    /**
     * Creates an instance of the given mongo-odm custom type.
     *
     * @psalm-template T of Type
     *
     * @psalm-param class-string<T> $type
     *
     * @psalm-return T
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress PossiblyInvalidArgument
     * @psalm-suppress PossibleInvalidStringClass
     * @psalm-suppress InvalidStringClass
     * @psalm-suppress InvalidReturnStatement
     */
    protected function createMongoType(string $type): Type
    {
        /* @var Type|string $type */
        $type::registerType($type, $type);

        return $type::getType($type);
    }

    protected function setUpTraits(): void
    {
        $traits = array_keys($this->classUsesRecursive(static::class));

        foreach ($traits as $trait) {
            $reflection = new ReflectionClass($trait);

            if (! str_starts_with($reflection->getShortName(), 'With')) {
                continue;
            }

            $methods = $reflection->getMethods(ReflectionMethod::IS_PROTECTED);
            foreach ($methods as $method) {
                if ('setUp' === $method->name) {
                    continue;
                }
                if (str_starts_with($method->name, 'setUp')) {
                    $this->{$method->name}();
                }
            }
        }
    }

    protected function tearDownTraits(): void
    {
        $traits = array_keys($this->classUsesRecursive(static::class));

        foreach ($traits as $trait) {
            $reflection = new ReflectionClass($trait);

            if (! str_starts_with($reflection->getShortName(), 'Creates')) {
                continue;
            }

            $methods = $reflection->getMethods(ReflectionMethod::IS_PROTECTED);
            foreach ($methods as $method) {
                if ('tearDown' === $method->name) {
                    continue;
                }
                if (str_starts_with($method->name, 'tearDown')) {
                    $this->{$method->name}();
                }
            }
        }
    }

    protected function classUsesRecursive(string $class): array
    {
        $traits = class_uses($class);

        foreach ($traits as $trait) {
            $traits += $this->classUsesRecursive($trait);
        }

        return array_unique($traits);
    }

    protected function tearDown(): void
    {
        $this->tearDownTraits();

        parent::tearDown();
    }
}
