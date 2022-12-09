<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Redis\Contract\SetInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends ApiController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(Request $request, SetInterface $set): Response
    {
        $tokenWithBearer = $request->headers->get('Authorization');

        $setKey = 'invalidTokens';
        $set->add($setKey, $tokenWithBearer);

        $data = [
            'message' => 'Done logout successfully',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
