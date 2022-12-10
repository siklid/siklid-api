<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth\Request;

use App\Foundation\Http\Request;
use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest extends Request
{
    public function formInput(): array
    {
        $data = [];
        if ($this->has('email') && ! empty($this->get('email'))) {
            $data['email'] = Email::fromString((string)$this->get('email'));
        }

        if ($this->has('username') && ! empty($this->get('username'))) {
            $data['username'] = Username::fromString((string)$this->get('username'));
        }

        if ($this->has('password') && ! empty($this->get('password'))) {
            $data['password'] = (string)$this->get('password');
        }

        return $data;
    }

    protected function constraints(): array
    {
        $notBlank = new Assert\NotBlank();

        return [
            'email' => [$notBlank, new Assert\Email()],
            'username' => [$notBlank],
            'password' => [$notBlank],
        ];
    }
}
