<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Siklid\Auth\RegisterByEmail;
use App\Siklid\Foundation\Http\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends ApiController
{
    #[Route('/auth/register/email', name: 'auth_register_email', methods: ['POST'])]
    public function byEmail(RegisterByEmail $action): Response
    {
        return $this->created([
            'user' => $action->execute(),
        ], ['user:read']);
    }
}
