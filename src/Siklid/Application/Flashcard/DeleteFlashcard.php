<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
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

    public function execute(): mixed
    {
        $flashcardId = (string)$this->request->get('id');
        $flashcard = $this->dm->getRepository(Flashcard::class)->find($flashcardId);

        assert($flashcard instanceof Flashcard, new NotFoundHttpException('Flashcard not found'));
        assert($flashcard->getUser() === $this->userResolver->getUser(), new UnauthorizedHttpException('You are not allowed to delete this flashcard'));

        $flashcard->delete();
        $this->dm->persist($flashcard);
        $this->dm->flush();

        return $flashcard;
    }
}
