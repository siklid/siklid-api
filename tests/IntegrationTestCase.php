<?php

declare(strict_types=1);

namespace App\Tests;

use App\Foundation\Util\Json;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
}
