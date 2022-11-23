<?php

declare(strict_types=1);

namespace App\Tests\Concern;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait FactoryConcern
{
    use WithFaker;

    abstract protected function container(): ContainerInterface;
}
