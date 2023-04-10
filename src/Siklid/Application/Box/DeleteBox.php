<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Authorization\AbstractVoter;
use App\Foundation\Security\Authorization\AuthorizationCheckerInterface as Auth;
use App\Siklid\Application\Contract\Entity\BoxInterface;
use App\Siklid\Document\Box;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeleteBox extends AbstractAction
{
    private DocumentManager $dm;
    private Request $request;
    private Auth $auth;

    public function __construct(DocumentManager $dm, Request $request, Auth $auth)
    {
        $this->dm = $dm;
        $this->request = $request;
        $this->auth = $auth;
    }

    /**
     * @throws MappingException
     * @throws MongoDBException
     * @throws LockException
     */
    public function execute(): BoxInterface
    {
        $boxRepository = $this->dm->getRepository(Box::class);
        $boxId = (string)$this->request->get('id');
        $box = $boxRepository->find($boxId);

        if (! $box) {
            throw new NotFoundHttpException('Box not found');
        }

        $this->auth->denyAccessUnlessGranted(AbstractVoter::DELETE, $box);

        $box->delete();

        $this->dm->persist($box);
        $this->dm->flush();

        return $box;
    }
}
