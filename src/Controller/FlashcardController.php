<?php

declare(strict_types=1);

namespace App\Controller;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Flashcard\CreateFlashcard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FlashcardController extends ApiController
{
    #[Route('/flashcards', name: 'flashcard_store', methods: ['POST'])]
    public function store(CreateFlashcard $action): JsonResponse
    {
        return $this->created($action->execute(), ['flashcard:create', 'resource:read']);
    }
}
