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

class LogoutController extends ApiController
{
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(Request $request, CacheInterface $cache)
    {
        $tokenWithBearer = $request->headers->get('Authorization');

        $tokenWithoutBearer = str_replace("Bearer ","",$tokenWithBearer);

        // TODO Remove this line
//        $cache = new FilesystemAdapter();

        $cacheItem = $cache->getItem('users.invalidToken');

        dd($cacheItem);

        if(!$cacheItem->isHit()){
            $invalidTokens = [];
            $invalidTokens[$tokenWithoutBearer] = time();
        }else {
            $invalidTokens = $cacheItem->get();
            $invalidTokens[$tokenWithoutBearer] = time();
        }

        $cacheItem->set($invalidTokens);

        $cache->save($cacheItem);

//        dd($cacheItem);

        $data = [
            'message' => 'Done logout successfully',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
