<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Validation\ValidatorInterface as Validator;
use App\Siklid\Application\Contract\Entity\FlashCardInterface;
use App\Siklid\Application\Flashcard\Request\CreateFlashcardRequest as Request;
use App\Siklid\Document\Box;
use App\Siklid\Document\Flashcard;
use App\Siklid\Repository\BoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentManager as DM;

class CreateFlashcard extends AbstractAction
{
    private Request $request;
    private DM $dm;
    private Validator $validator;

    public function __construct(Request $request, DM $dm, Validator $validator)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->validator = $validator;
    }

    public function execute(): FlashCardInterface
    {
        $flashcard = new Flashcard();
        $flashcard->setUser($this->getUser());
        $flashcard->setFront($this->request->front());
        $flashcard->setBack($this->request->back());
        $flashcard->setBoxes($this->boxes());

        $this->validator->validate($flashcard);

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
