<?php

namespace App\Siklid\Auth\Requests;

use App\Foundation\Http\Request;

final class RegisterByEmailRequest extends Request
{
    public function formInput(): array
    {
        return (array)($this->all()['user'] ?? []);
    }
}
