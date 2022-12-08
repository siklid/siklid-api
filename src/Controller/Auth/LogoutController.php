<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Redis\Contract\SetInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class LogoutController extends ApiController
{
    protected array $invalidTokens = array();
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(Request $request, CacheInterface $cache, SetInterface $set): Response
    {
        $tokenWithBearer = $request->headers->get('Authorization');

//        $tokenWithoutBearer = str_replace('Bearer ', '', $tokenWithBearer);

        $setKey = 'invalidTokens';
        $set->add($setKey, $tokenWithBearer);

//        $values = $set->members($setKey);
//
//
//        $values = $set->members($setKey1);
//        $cacheItem = $cache->getItem('users.invalidToken');
//
//        if (! $cacheItem->isHit()) {
//            $this->invalidTokens[$tokenWithoutBearer] = time();
//        } else {
//            $this->invalidTokens = $cacheItem->get();
//            $this->invalidTokens[$tokenWithoutBearer] = time();
//        }
//
//        $cacheItem->set($this->invalidTokens);
//
//        $cache->save($cacheItem);

        $data = [
            'message' => 'Done logout successfully',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
