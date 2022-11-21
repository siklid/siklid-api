<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Class TestCase
 * All Unit tests should extend this class.
 *
 * @psalm-suppress MissingConstructor
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Faker $faker;

    protected Json $json;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();

        $this->json = new Json();
    }

    /**
     * Creates an instance of the given mongo-odm custom type.
     *
     * @param class-string<T|Type> $type the type to create
     *
     * @return T
     *
     * @template T|Type
     */
    protected function createMongoType(string $type): Type
    {
        $type::registerType($type, $type);

        return $type::getType($type);
    }
}
