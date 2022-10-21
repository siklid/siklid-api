<?php

declare(strict_types=1);

namespace App\Siklid\Auth\Request;

use App\Siklid\Foundation\Http\Request;

final class RegisterByEmailRequest extends Request
{
    public function formInput(): array
    {
        return (array) ($this->all()['user'] ?? []);
    }
}
