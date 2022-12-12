<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth\Request;

use App\Foundation\Http\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class LogoutRequest extends Request
{
    protected function constraints(): array
    {
        $notBlank = new Assert\NotBlank();

        return [
            'refreshToken' => [$notBlank],
        ];
    }
}
