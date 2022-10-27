<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;

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
}
