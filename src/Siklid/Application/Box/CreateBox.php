<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Form\BoxType;
use Doctrine\ODM\MongoDB\DocumentManager;

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

        /** @var BoxInterface $box */
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
        preg_match_all('/#(\S+)/', $text, $matches);
        foreach ($matches[1] as $match) {
            $match = mb_strtolower($match);
            $hashtags[] = '#'.$match;
        }

        return $hashtags;
    }
}
