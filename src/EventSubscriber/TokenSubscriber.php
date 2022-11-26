<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\CacheInterface;

class TokenSubscriber implements EventSubscriberInterface
{
    private $tokens;
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $this->tokens =  $this->cache->getItem('users.invalidToken')->get();
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof TokenAuthenticatedInterface) {

            $tokenWithBearer = $event->getRequest()->headers->get('Authorization');
            $tokenWithoutBearer = str_replace('Bearer ', '', $tokenWithBearer);

            if (isset($this->tokens[$tokenWithoutBearer])) {
                $data = [
                    "code"=> Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid JWT Token',
                ];

                $event->setController(function() use ($data){
                    return new JsonResponse(json_encode($data), Response::HTTP_UNAUTHORIZED, [], true);
                });


            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}