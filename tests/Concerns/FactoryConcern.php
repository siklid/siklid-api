<?php

declare(strict_types=1);

namespace App\Tests\Concerns;

use App\Tests\Faker;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait FactoryConcern
{
    abstract protected function container(): ContainerInterface;

    abstract protected function faker(): Faker;
}
