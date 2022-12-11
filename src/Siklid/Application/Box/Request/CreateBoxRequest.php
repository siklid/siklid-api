<?php

declare(strict_types=1);

namespace App\Siklid\Application\Box\Request;

use App\Foundation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateBoxRequest extends Request
{
    protected function constraints(): array
    {
        return [
            'name' => [new Assert\NotBlank()],
        ];
    }
}
