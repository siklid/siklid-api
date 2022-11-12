<?php

declare(strict_types=1);

namespace App\Controller;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Box\CreateBox;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoxController extends ApiController
{
    #[Route('/boxes', name: 'api_v1_boxes_create', methods: ['POST'])]
    public function store(CreateBox $action): Response
    {
        return $this->created($action->execute(), ['box:read']);
    }
}
