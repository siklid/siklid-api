<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Authorization\AuthorizationCheckerInterface as Auth;
use App\Siklid\Application\Contract\Entity\FlashcardInterface;
use App\Siklid\Document\Flashcard;
use Doctrine\ODM\MongoDB\DocumentManager as DM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteFlashcard extends AbstractAction
{
    private Request $request;
    private DM $dm;
    private Auth $auth;

    public function __construct(Request $request, DM $dm, Auth $auth)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->auth = $auth;
    }

    public function execute(): FlashcardInterface
    {
        $flashcardId = (string)$this->request->get('id');
        $flashcard = $this->dm->getRepository(Flashcard::class)->find($flashcardId);

        if (! $flashcard instanceof Flashcard) {
            throw new NotFoundHttpException('Flashcard not found');
        }

        $this->auth->denyAccessUnlessGranted('delete', $flashcard);

        $flashcard->delete();
        $this->dm->persist($flashcard);
        $this->dm->flush();

        return $flashcard;
    }
}
