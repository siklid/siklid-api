<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Pagination\Contract\PageInterface;
use App\Siklid\Document\Box;
use App\Siklid\Repository\BoxRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * This action is used to list all boxes.
 */
final class ListBoxes extends AbstractAction
{
    private DocumentManager $dm;

    private Request $request;

    public function __construct(DocumentManager $dm, Request $request)
    {
        $this->dm = $dm;
        $this->request = $request;
    }

    public function execute(): PageInterface
    {
        $boxRepository = $this->dm->getRepository(Box::class);
        assert($boxRepository instanceof BoxRepository);

        $after = (string)$this->request->get('after');
        $hashtag = $this->request->get('hashtag');
        if (null !== $hashtag) {
            $hashtag = (string)$hashtag;
        }
        $limit = (int)$this->getConfig('pagination.limit', 25);
        if ($this->request->has('size')) {
            $limit = (int)$this->request->get('size');
        }

        return $boxRepository->PaginateAfter($after, $hashtag, $limit);
    }
}
