<?php

declare(strict_types=1);

namespace App\Foundation\Pagination\Contract;

use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * All pagination classes must implement this interface.
 */
interface PaginatorInterface
{
    public function paginate(Builder $builder, string $offset, int $limit): PageInterface;
}
