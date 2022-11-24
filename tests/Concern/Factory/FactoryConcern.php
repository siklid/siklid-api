<?php

declare(strict_types=1);

namespace App\Tests\Concern\Factory;

use App\Tests\Concern\WithFaker;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This trait should be used by all factory traits.
 * It makes sure that the container is available and that the faker is loaded.
 */
trait FactoryConcern
{
    use WithFaker;

    abstract protected function container(): ContainerInterface;
}
