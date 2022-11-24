<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ODM\MongoDB\Types\Type;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * All Tests should extend this class, and use traits to add functionality.
 *
 * @psalm-suppress MissingConstructor
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->invokeTemplateMethods('setUp');
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

    /**
     * Invokes all methods that start with the given prefix.
     *
     * @throws ReflectionException
     */
    protected function invokeTemplateMethods(string $prefix): void
    {
        $traits = array_keys($this->classUsesRecursive(static::class));

        foreach ($traits as $trait) {
            $reflection = new ReflectionClass($trait);

            $methods = $this->filterTemplateMethods($reflection, $prefix);

            foreach ($methods as $method) {
                $this->{$method->name}();
            }
        }
    }

    /**
     * Finds all traits used by a trait and its traits.
     *
     * @return array<class-string, class-string>
     */
    protected function classUsesRecursive(string $class): array
    {
        /** @var array<class-string, class-string> $traits */
        $traits = class_uses($class);

        foreach ($traits as $trait) {
            $traits += $this->classUsesRecursive($trait);
        }

        return array_unique($traits);
    }

    /**
     * @return ReflectionMethod[]
     */
    protected function filterTemplateMethods(ReflectionClass $trait, string $template): array
    {
        return array_filter(
            $trait->getMethods(),
            static fn (ReflectionMethod $method) => ! (
                $template === $method->name ||
                ! str_starts_with($method->name, $template))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->invokeTemplateMethods('tearDown');

        parent::tearDown();
    }
}
