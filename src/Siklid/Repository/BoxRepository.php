<?php

declare(strict_types=1);

namespace App\Siklid\Repository;

use App\Foundation\Pagination\Contract\PageInterface;
use App\Foundation\Pagination\CursorPaginator;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class BoxRepository extends DocumentRepository
{
    public function PaginateAfter(string $after = '', ?string $hashtag = null, int $perPage = 25): PageInterface
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('id', 'DESC')
            ->field('user')->prime();

        if (null !== $hashtag) {
            $qb->field('hashtags')->equals('#'.$hashtag);
        }

        return CursorPaginator::create()->paginate($qb, $after, $perPage);
    }
}
