<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth\Request;

use App\Foundation\Http\Request;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
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
