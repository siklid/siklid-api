<?php

declare(strict_types=1);

namespace App\Siklid\Repository;

use App\Foundation\Pagination\Contract\PageInterface;
use App\Foundation\Pagination\CursorPaginator;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class BoxRepository extends DocumentRepository
{
    public function PaginateAfter(string $after = '', int $perPage = 25): PageInterface
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('id', 'DESC')
            ->field('user')->prime();

        return CursorPaginator::create()->paginate($qb, $after, $perPage);
    }
}
