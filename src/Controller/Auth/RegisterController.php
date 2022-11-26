<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Auth\RegisterByEmail;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

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

}
