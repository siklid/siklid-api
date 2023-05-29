<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth\Request;

use App\Foundation\Validation\Constraint\Exists;
use App\Siklid\Document\RefreshToken;
use Symblaze\Bundle\Http\Request\ValidatAbleRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class LogoutRequest extends ValidatAbleRequest
{
    public function refreshToken(): string
    {
        return (string)$this->input('refreshToken');
    }

    public function getAccessToken(): string
    {
        $accessToken = (string)$this->header('Authorization');

        return str_replace('Bearer ', '', $accessToken);
    }

    public function constraints(): array
    {
        $notBlank = new Assert\NotBlank();
        $exist = new Exists(RefreshToken::class, 'refreshToken');

        return [
            'refreshToken' => [$notBlank, $exist],
        ];
    }
}
