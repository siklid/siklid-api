<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth\Request;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use Symblaze\Bundle\Http\Request\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest extends Request
{
    /**
     * @return array<string, mixed>
     */
    public function optimizedInput(): array
    {
        $data = [];
        if ($this->has('email') && ! empty($this->input('email'))) {
            $data['email'] = Email::fromString((string)$this->input('email'));
        }

        if ($this->has('username') && ! empty($this->input('username'))) {
            $data['username'] = Username::fromString((string)$this->input('username'));
        }

        if ($this->has('password') && ! empty($this->input('password'))) {
            $data['password'] = (string)$this->input('password');
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
