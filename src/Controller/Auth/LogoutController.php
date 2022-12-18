<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Siklid\Application\Auth\DeleteRefreshToken;
use App\Siklid\Application\Auth\InvalidAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends ApiController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(InvalidAccessToken $invalidAccessTokenAction, DeleteRefreshToken $deleteRefreshTokenAction): Response
    {
        $invalidAccessTokenAction->execute();

        $deleteRefreshTokenAction->execute();

        $data = [
            'message' => 'You have been logged out.',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
