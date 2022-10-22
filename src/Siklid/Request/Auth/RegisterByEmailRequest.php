<?php

declare(strict_types=1);

namespace App\Siklid\Request\Auth;

use App\Foundation\Http\Request;

final class RegisterByEmailRequest extends Request
{
    public function formInput(): array
    {
        return (array)($this->all()['user'] ?? []);
    }
}
