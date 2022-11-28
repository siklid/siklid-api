<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Document\Box;
use App\Siklid\Form\BoxType;
use Doctrine\ODM\MongoDB\DocumentManager;
use function Symfony\Component\String\u;

final class CreateBox extends AbstractAction
{
    private Request $request;
    private DocumentManager $dm;

    public function __construct(Request $request, DocumentManager $dm)
    {
        $this->request = $request;
        $this->dm = $dm;
    }

    public function execute(): BoxInterface
    {
        $form = $this->createForm(BoxType::class);
        $this->validate($form, $this->request);

        /** @var Box $box */
        $box = $form->getData();
        $box->setUser($this->getUser());
        $box->setHashtags($this->extractHashtags($box->getDescription()));

        $this->dm->persist($box);
        $this->dm->flush();

        return $box;
    }

    private function extractHashtags(?string $text): array
    {
        if (null === $text) {
            return [];
        }

        $hashtags = [];
        preg_match('/(^|\B)#(?![0-9_]+\b)([a-zA-Z0-9_]{1,30})(\b|\r)/', $text, $matches);
        foreach ($matches[0] as $match) {
            $hashtags[] = u(mb_strtolower($match))->append(' ')->toString();
        }

        return $hashtags;
    }
}
