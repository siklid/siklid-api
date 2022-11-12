<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Siklid\Document\Box;
use App\Siklid\Form\BoxType;
use Doctrine\ODM\MongoDB\DocumentManager;

class CreateBox extends AbstractAction
{
    private Request $request;
    private DocumentManager $dm;

    public function __construct(Request $request, DocumentManager $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function execute(): Box
    {
        $form = $this->createForm(BoxType::class);
        $this->validate($form, $this->request);

        /** @var Box $box */
        $box = $form->getData();
        $box->setUser($this->getUser());

        $this->dm->persist($box);
        $this->dm->flush();

        return $box;
    }
}
