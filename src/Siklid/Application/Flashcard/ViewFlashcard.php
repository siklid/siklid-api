<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Siklid\Application\Contract\Entity\FlashcardInterface;
use App\Siklid\Document\Flashcard;
use Doctrine\ODM\MongoDB\DocumentManager as DM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewFlashcard extends AbstractAction
{
    private DM $dm;

    private Request $request;

    public function __construct(Request $request, DM $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function execute(): FlashcardInterface
    {
        $flashcardId = (string)$this->request->get('id');
        $flashcard = $this->dm->getRepository(Flashcard::class)->find($flashcardId);

        if (! $flashcard instanceof Flashcard) {
            throw new NotFoundHttpException('Flashcard not found');
        }

        return $flashcard;
    }
}
