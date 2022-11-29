<?php

declare(strict_types=1);

namespace App\Foundation\Pagination\Contract;

/**
 * Objects implementing this interface represent a single page of a paginated collection.
 */
interface PageInterface
{
    public function getData(): array;

    public function getLinks(): array;

    public function getMeta(): array;
}
