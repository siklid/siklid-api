<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Siklid\Foundation\Http\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends ApiController
{
    #[Route('/auth/register/email', name: 'auth_register_email', methods: ['POST'])]
    public function byEmail(\App\Siklid\Application\Auth\RegisterByEmail $action): Response
    {
        $user = $action->execute();

        $data = [
            'user' => $user,
            'token' => $user->getAccessToken(),
        ];

        $groups = ['user:read', 'token:read'];

        return $this->created($data, $groups);
    }
}
