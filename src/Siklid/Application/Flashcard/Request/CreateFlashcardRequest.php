<?php

declare(strict_types=1);

namespace App\Siklid\Application\Flashcard\Request;

use Symblaze\Bundle\Http\Request\ValidatableRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFlashcardRequest extends ValidatableRequest
{
    public function constraints(): array
    {
        $notBlank = new Assert\NotBlank();

        return [
            'back' => [
                $notBlank,
            ],
            'boxes' => [
                $notBlank,
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
            ],
        ];
    }

    public function getBoxes(): array
    {
        return (array)$this->all()['boxes'];
    }

    public function front(): ?string
    {
        $front = $this->input('front');
        assert(is_string($front) || is_null($front));

        return $front;
    }

    public function back(): string
    {
        return (string)$this->input('back');
    }
}
