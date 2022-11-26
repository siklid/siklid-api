<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\TokenAuthenticatedInterface;
use App\Foundation\Http\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class LogoutInterface extends ApiController implements TokenAuthenticatedInterface
{
    protected array $invalidTokens = array();
    #[Route('/auth/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(Request $request, CacheInterface $cache): Response
    {
//        dd('logout method');
        $tokenWithBearer = $request->headers->get('Authorization');

        $tokenWithoutBearer = str_replace('Bearer ', '', $tokenWithBearer);

        $cacheItem = $cache->getItem('users.invalidToken');

        if (! $cacheItem->isHit()) {
            $this->invalidTokens[$tokenWithoutBearer] = time();
        } else {
            $this->invalidTokens = $cacheItem->get();
            $this->invalidTokens[$tokenWithoutBearer] = time();
        }

        $cacheItem->set($this->invalidTokens);

        $cache->save($cacheItem);

        $data = [
            'message' => 'Done logout successfully',
        ];

        return $this->json($data, Response::HTTP_OK);
    }
}
