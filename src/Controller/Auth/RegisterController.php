<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Siklid\Auth\RegisterByEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends ApiController
{
    #[Route('/auth/register/email', name: 'auth_register_email', methods: ['POST'])]
    public function byEmail(RegisterByEmail $action): Response
    {
        return $this->created([
            'user' => $action->execute(),
        ]);
    }
}
