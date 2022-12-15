<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Siklid\Application\Contract\Entity\FlashCardInterface;
use App\Siklid\Application\Flashcard\Request\CreateFlashcardRequest;
use App\Siklid\Document\Box;
use App\Siklid\Document\Flashcard;
use App\Siklid\Repository\BoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager;

class CreateFlashcard extends AbstractAction
{
    private CreateFlashcardRequest $request;
    private DocumentManager $dm;

    public function __construct(CreateFlashcardRequest $request, DocumentManager $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function execute(): FlashCardInterface
    {
        $flashcard = new Flashcard();
        $flashcard->setUser($this->getUser());
        $flashcard->setFront($this->request->front());
        $flashcard->setBack($this->request->back());
        $flashcard->setBoxes($this->boxes());

        $this->dm->persist($flashcard);
        $this->dm->flush();

        return $flashcard;
    }

    private function boxes(): Collection
    {
        $boxRepository = $this->dm->getRepository(Box::class);
        assert($boxRepository instanceof BoxRepository);
        $boxes = $boxRepository->findByUserAndIds($this->getUser(), $this->request->getBoxes());

        return new ArrayCollection($boxes);
    }
}
