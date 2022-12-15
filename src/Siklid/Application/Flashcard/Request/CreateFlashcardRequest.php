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
        $front = $this->get('front');
        assert(is_string($front) || is_null($front));

        return $front;
    }

    public function back(): string
    {
        return (string)$this->get('back');
    }
}
