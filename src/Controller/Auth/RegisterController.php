<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Auth\RegisterByEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RegisterController extends ApiController
{
    #[Route('/auth/register/email', name: 'auth_register_email', methods: ['POST'])]
    public function byEmail(RegisterByEmail $action): Response
    {
        $user = $action->execute();

        $data = [
            'user' => $user,
            'token' => $user->getAccessToken(),
        ];

        $groups = ['user:read', 'token:read'];

        return $this->created($data, $groups);
    }

    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(TokenStorageInterface $tokenStorage): Response
    {
        $tokenStorage->setToken();

        return $this->json('done logout successfully.');
    }
}
