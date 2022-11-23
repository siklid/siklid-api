<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\Concern\SetupTraits;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Class TestCase
 * All Unit tests should extend this class.
 *
 * @psalm-suppress MissingConstructor
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    use SetupTraits;

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
}
