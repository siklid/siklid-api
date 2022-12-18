<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Util\Hashtag;
use App\Foundation\Validation\ValidatorInterface;
use App\Siklid\Application\Box\Request\CreateBoxRequest;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Document\Box;
use Doctrine\ODM\MongoDB\DocumentManager;

use function Symfony\Component\String\u;

final class CreateBox extends AbstractAction
{
    private CreateBoxRequest $request;

    private DocumentManager $dm;

    private ValidatorInterface $validator;
    private Hashtag $hashtag;

    public function __construct(
        CreateBoxRequest $request,
        DocumentManager $dm,
        ValidatorInterface $validator,
        Hashtag $hashtag
    ) {
        $this->request = $request;
        $this->dm = $dm;
        $this->validator = $validator;
        $this->hashtag = $hashtag;
    }

    public function execute(): BoxInterface
    {
        $box = $this->fill(Box::class, $this->request->formInput());
        $box->setUser($this->getUser());
        $box->setHashtags($this->hashtag->extract((string)$box->getDescription()));

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
