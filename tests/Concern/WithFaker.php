<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Tests\Faker;

trait WithFaker
{
    protected Faker $faker;

    protected function setUpFaker(): void
    {
        $this->faker = Faker::create();
    }

    protected function faker(?string $locale = null): Faker
    {
        if (null === $locale) {
            return $this->faker;
        }

        return Faker::create($locale);
    }
}
