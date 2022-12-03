<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Validation\ValidatorInterface;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Document\Box;
use Doctrine\ODM\MongoDB\DocumentManager;

use function Symfony\Component\String\u;

final class CreateBox extends AbstractAction
{
    private Request $request;

    private DocumentManager $dm;

    private ValidatorInterface $validator;

    public function __construct(Request $request, DocumentManager $dm, ValidatorInterface $validator)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->validator = $validator;
    }

    public function execute(): BoxInterface
    {
        $box = new Box();
        $box->setName((string)$this->request->get('name'));
        $description = $this->request->get('description');
        assert(is_string($description) || is_null($description));
        $box->setDescription($description);
        $box->setUser($this->getUser());
        $box->setHashtags($this->extractHashtags($box->getDescription()));

        $this->validator->validate($box);

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
        preg_match_all('/(^|\B)#(?![0-9_]+\b)([a-zA-Z0-9_]|\p{Arabic}){1,30}(\b|\r)/u', $text, $matches);
        foreach ($matches[0] as $match) {
            $hashtags[] = u(mb_strtolower($match))->toString();
        }

        return $hashtags;
    }
}
