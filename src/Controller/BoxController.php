<?php

declare(strict_types=1);

namespace App\Controller;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Box\CreateBox;
use App\Siklid\Application\Box\DeleteBox;
use App\Siklid\Document\Box;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoxController extends ApiController
{
    #[Route('/boxes', name: 'api_v1_boxes_create', methods: ['POST'])]
    public function store(CreateBox $action): Response
    {
        return $this->created($action->execute(), ['box:read']);
    }

    #[Route('/boxes/{id}', name: 'api_v1_boxes_delete', methods: ['DELETE'])]
    #[IsGranted('delete', subject: 'box')]
    public function delete(DeleteBox $action, Box $box): Response
    {
        return $this->ok($action->setBox($box)->execute());
    }
}
