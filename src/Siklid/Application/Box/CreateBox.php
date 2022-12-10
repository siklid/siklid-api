<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Util\Hashtag;
use App\Foundation\Validation\ValidatorInterface;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Document\Box;
use Doctrine\ODM\MongoDB\DocumentManager;

final class CreateBox extends AbstractAction
{
    private Request $request;

    private DocumentManager $dm;

    private ValidatorInterface $validator;
    private Hashtag $hashtag;

    public function __construct(Request $request, DocumentManager $dm, ValidatorInterface $validator, Hashtag $hashtag)
    {
        $this->request = $request;
        $this->dm = $dm;
        $this->validator = $validator;
        $this->hashtag = $hashtag;
    }

    public function execute(): BoxInterface
    {
        $box = new Box();
        $box->setName((string)$this->request->get('name'));
        $description = $this->request->get('description');
        assert(is_string($description) || is_null($description));
        $box->setDescription($description);
        $box->setUser($this->getUser());
        $hashtags = $this->hashtag->extract((string)$description);
        $box->setHashtags($hashtags);

        $this->validator->validate($box);

        $this->dm->persist($box);
        $this->dm->flush();

        return $box;
    }
}
