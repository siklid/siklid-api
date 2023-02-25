<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Siklid\Application\Contract\Entity\FlashCardInterface;
use App\Siklid\Document\Flashcard;
use App\Siklid\Security\UserResolverInterface as UserResolver;
use Doctrine\ODM\MongoDB\DocumentManager as DM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DeleteFlashcard extends AbstractAction
{
    private Request $request;
    private DM $dm;
    private UserResolver $userResolver;

    public function __construct(Request $request, DM $dm, UserResolver $userResolver)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->userResolver = $userResolver;
    }

    public function execute(): FlashCardInterface
    {
        $flashcardId = (string)$this->request->get('id');
        $flashcard = $this->dm->getRepository(Flashcard::class)->find($flashcardId);

        if (! $flashcard instanceof Flashcard) {
            throw new NotFoundHttpException('Flashcard not found');
        }

        if ($flashcard->getUser() !== $this->userResolver->getUser()) {
            throw new UnauthorizedHttpException('You are not allowed to delete this flashcard');
        }

        $flashcard->delete();
        $this->dm->persist($flashcard);
        $this->dm->flush();

        return $flashcard;
    }
}
