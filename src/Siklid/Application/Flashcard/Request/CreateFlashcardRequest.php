<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard\Request;

use App\Foundation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFlashcardRequest extends Request
{
    protected function constraints(): array
    {
        $notBlank = new Assert\NotBlank();

        return [
            'backside' => [
                $notBlank,
            ],
            'boxes' => [
                $notBlank,
                new Assert\Type('array'),
            ],
        ];
    }
}
