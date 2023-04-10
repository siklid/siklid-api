<?php

declare(strict_types=1);

namespace App\Siklid\Repository;

use App\Foundation\Pagination\Contract\PageInterface;
use App\Foundation\Pagination\CursorPaginator;
use App\Siklid\Application\Contract\Entity\UserInterface;
use App\Siklid\Document\Box;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

/**
 * @template-extends DocumentRepository<Box>
 */
class BoxRepository extends DocumentRepository
{
    public function PaginateAfter(string $after = '', ?string $hashtag = null, int $perPage = 25): PageInterface
    {
        $qb = $this->createQueryBuilder();
        $qb->sort('id', 'DESC')
            ->field('user')->prime();

        if (null !== $hashtag) {
            $qb->field('hashtags')->equals($hashtag);
        }

        return CursorPaginator::create()->paginate($qb, $after, $perPage);
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion - We know that the result is an array of Box
     * @return Box[]
     */
    public function findByUserAndIds(UserInterface $getUser, array $ids): array
    {
        return $this->findBy([
            'user' => $getUser,
            'id' => ['$in' => $ids],
        ]);
    }
}
