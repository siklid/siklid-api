<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Authorization\AuthorizationCheckerInterface as AuthChecker;
use App\Siklid\Application\Contract\Entity\FlashcardInterface;
use App\Siklid\Document\Flashcard;
use Doctrine\ODM\MongoDB\DocumentManager as DM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteFlashcard extends AbstractAction
{
    private Request $request;
    private DM $dm;
    private AuthChecker $authChecker;

    public function __construct(Request $request, DM $dm, AuthChecker $authChecker)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->authChecker = $authChecker;
    }

    public function execute(): FlashcardInterface
    {
        $flashcardId = (string)$this->request->get('id');
        $flashcard = $this->dm->getRepository(Flashcard::class)->find($flashcardId);

        if (! $flashcard instanceof Flashcard) {
            throw new NotFoundHttpException('Flashcard not found');
        }

        $this->authChecker->denyAccessUnlessGranted('delete', $flashcard);

        $flashcard->delete();
        $this->dm->persist($flashcard);
        $this->dm->flush();

        return $flashcard;
    }
}
