<?php

namespace App\EventSubscriber;

use App\Controller\TokenAuthenticatedInterface;
use App\Foundation\Redis\Contract\SetInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\CacheInterface;

class TokenSubscriber implements EventSubscriberInterface
{
//    private $tokens;
//    private $cache;
    private $set;

    public function __construct(SetInterface $set)
    {
        $this->set = $set;
    }

    public function onKernelController(ControllerEvent $event)
    {
//        $this->tokens =  $this->cache->getItem('users.invalidToken')->get();
//        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
//        if (is_array($controller)) {
//            $controller = $controller[0];
//        }

        if ($event->getRequest()->headers->has('Authorization')) {

            $values = $this->set->members('invalidTokens');
            $tokenWithBearer = $event->getRequest()->headers->get('Authorization');
//            dd($values, $tokenWithBearer, isset($values[$tokenWithBearer]));
            if(in_array($tokenWithBearer, $values)){
                $data = [
                    "code"=> Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid JWT Token',
                ];

                $event->setController(function() use ($data){
                    return new JsonResponse(json_encode($data), Response::HTTP_UNAUTHORIZED, [], true);
                });
            }

//            $tokenWithoutBearer = str_replace('Bearer ', '', $tokenWithBearer);
//
//            if (isset($this->tokens[$tokenWithoutBearer])) {
//                $data = [
//                    "code"=> Response::HTTP_UNAUTHORIZED,
//                    'message' => 'Invalid JWT Token',
//                ];
//
//                $event->setController(function() use ($data){
//                    return new JsonResponse(json_encode($data), Response::HTTP_UNAUTHORIZED, [], true);
//                });
//
//
//            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}