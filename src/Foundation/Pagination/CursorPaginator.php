<?php

declare(strict_types=1);

namespace App\Foundation\Pagination;

use App\Foundation\Pagination\Contract\PageInterface;
use App\Foundation\Pagination\Contract\PaginatorInterface;
use Doctrine\ODM\MongoDB\Query\Builder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cursor paginator uses a cursor to paginate through a list of items.
 * The cursor is a string that represents the last item of the previous page.
 */
final class CursorPaginator implements PaginatorInterface
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function create(?Request $request = null): self
    {
        return new self($request ?? Request::createFromGlobals());
    }

    /**
     * Paginates a query builder.
     *
     * @param builder $builder - The query builder to paginate
     * @param string $offset   - Used as the cursor
     * @param int $limit       - The number of items to return per page
     *
     * @return pageInterface - The paginated page
     */
    public function paginate(Builder $builder, string $offset = '', int $limit = 25): PageInterface
    {
        $cursor = empty($offset) ? (string)$this->request->query->get('after') : $offset;

        if (! empty($cursor)) {
            $builder->field('id')->lt($cursor);
        }

        $builder->limit($limit);

        $data = $builder->getQuery()->toArray();
        $links = $this->buildLinks($data);
        $meta = ['perPage' => $limit, 'count' => count($data)];

        return Page::init()->data($data)->links($links)->meta($meta);
    }

    private function buildLinks(array $data): array
    {
        $next = null;

        /** @var object|null $lastItem */
        $lastItem = end($data);
        if (is_object($lastItem) && method_exists($lastItem, 'getId')) {
            $lastItemCursor = (string)$lastItem->getId();
            $query = $this->request->query->all();
            $query['after'] = $lastItemCursor;
            $next = $this->request->getUriForPath($this->request->getPathInfo()).'?'.http_build_query($query);
        }

        return [
            'self' => $this->request->getUri(),
            'next' => $next,
        ];
    }
}
