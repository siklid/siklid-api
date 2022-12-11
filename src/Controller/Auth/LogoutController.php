<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Redis\Contract\SetInterface;
use App\Siklid\Application\Auth\Request\LogoutRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends ApiController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(LogoutRequest $request, SetInterface $set): Response
    {

//        dd($request->all()['refreshToken']);
        $tokenWithBearer = $request->request()->headers->get('Authorization');
        $userId = $this->getUser()->getId();

        $setKey = 'user.'.$userId.'.accessToken';
        $set->add($setKey, $tokenWithBearer);
        $set->setTtl($setKey, time()+(60*60));

        $data = [
            'message' => 'You have been logged out.',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
