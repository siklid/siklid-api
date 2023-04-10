<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Exception\SiklidException;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

final class DeleteBox extends AbstractAction
{
    private ?BoxInterface $box;
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm, ?BoxInterface $box = null)
    {
        $this->dm = $dm;
        $this->box = $box;
    }

    public function execute(): BoxInterface
    {
        if (null === $this->box) {
            throw new SiklidException('Box is not set');
        }

        $this->box->delete();

        $this->dm->persist($this->box);
        $this->dm->flush();

        return $this->box;
    }

    public function setBox(BoxInterface $box): self
    {
        $this->box = $box;

        return $this;
    }
}
