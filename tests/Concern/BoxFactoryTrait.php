<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use App\Siklid\Document\Box;
use App\Siklid\Document\User;

/**
 * This trait is used to create boxes for testing purposes.
 */
trait BoxFactoryTrait
{
    use FactoryConcern;

    protected function makeBox(array $attributes = []): Box
    {
        $box = new Box();

        $box->setName($attributes['name'] ?? $this->faker()->word());
        $box->setDescription($attributes['description'] ?? $this->faker()->sentence());
        $box->setUser($attributes['user'] ?? new User());

        return $box;
    }
}
