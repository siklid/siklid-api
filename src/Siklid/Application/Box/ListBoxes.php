<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Exception\ValidationException;
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
        $after = $this->getAfterCursor();
        $hashtag = $this->getHashtag();
        $limit = $this->getLimit();

        $boxRepository = $this->dm->getRepository(Box::class);
        assert($boxRepository instanceof BoxRepository);

        return $boxRepository->PaginateAfter($after, $hashtag, $limit);
    }

    private function validateSize(int $limit): void
    {
        if ($limit < 1) {
            throw new ValidationException('Size must be 1 or greater.');
        }

        $maxSize = (int)$this->getConfig('pagination.max_limit', 100);
        if ($limit > $maxSize) {
            throw new ValidationException("Size must be less than or equal to $maxSize.");
        }
    }

    private function getAfterCursor(): string
    {
        return (string)$this->request->get('after');
    }

    private function getHashtag(): ?string
    {
        $hashtag = $this->request->get('hashtag');

        if (empty($hashtag)) {
            return null;
        }

        return '#'.$hashtag;
    }

    private function getLimit(): int
    {
        $limit = (int)$this->getConfig('pagination.limit', 25);

        if ($this->request->has('size')) {
            $limit = (int)$this->request->get('size');
            $this->validateSize($limit);
        }

        return $limit;
    }
}
