<?php

declare(strict_types=1);

namespace App\Controller;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Flashcard\Request\CreateFlashcardRequest;
use Symfony\Component\Routing\Annotation\Route;

class FlashcardController extends ApiController
{
    #[Route('/flashcards', name: 'flashcard_store', methods: ['POST'])]
    public function store(CreateFlashcardRequest $request): void
    {
    }
}
