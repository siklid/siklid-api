<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Foundation\Redis\Contract\SetInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenSubscriber implements EventSubscriberInterface
{
    private $set;

    public function __construct(SetInterface $set)
    {
        $this->set = $set;
    }

    public function onKernelController(ControllerEvent $event): bool|Response
    {
        if ($event->getRequest()->headers->has('Authorization')) {
            $values = $this->set->members('invalidTokens');
            $tokenWithBearer = $event->getRequest()->headers->get('Authorization');

            if (in_array($tokenWithBearer, $values)) {
                $data = [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid JWT Token',
                ];

                $event->setController(function () use ($data) {
                    return new JsonResponse(json_encode($data), Response::HTTP_UNAUTHORIZED, [], true);
                });
            }
        }

        return true;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
