<?php

declare(strict_types=1);

namespace App\Controller;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Box\CreateBox;
use App\Siklid\Application\Box\DeleteBox;
use App\Siklid\Application\Box\ListBoxes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoxController extends ApiController
{
    #[Route('/boxes', name: 'box_store', methods: ['POST'])]
    public function store(CreateBox $action): Response
    {
        return $this->created($action->execute(), ['box:create']);
    }

    #[Route('/boxes/{id}', name: 'box_delete', methods: ['DELETE'])]
    public function delete(DeleteBox $action): Response
    {
        return $this->ok($action->execute(), ['box:delete']);
    }

    #[Route('/boxes', name: 'box_index', methods: ['GET'])]
    public function index(ListBoxes $action): Response
    {
        return $this->ok($action->execute(), ['box:index', 'resource:read']);
    }
}
