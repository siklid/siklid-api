<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box\Request;

use Symblaze\Bundle\Http\Request\ValidatableRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateBoxRequest extends ValidatableRequest
{
    public function constraints(): array
    {
        return [
            'name' => [new Assert\NotBlank()],
        ];
    }
}
