<?php

declare(strict_types=1);

namespace App\Tests\Concern\Util;

/**
 * A trait for tests that need to use Faker.
 */
trait WithFaker
{
    protected Faker $faker;

    /**
     * Custom template method to set up the faker utility.
     */
    protected function setUpFaker(): void
    {
        $this->faker = Faker::create();
    }

    /**
     * Returns the faker utility or creates a new instance if locale is specified.
     */
    protected function faker(?string $locale = null): Faker
    {
        if (null === $locale) {
            return $this->faker;
        }

        return Faker::create($locale);
    }
}
