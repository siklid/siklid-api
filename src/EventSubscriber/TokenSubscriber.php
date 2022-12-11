<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Foundation\Redis\Contract\SetInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TokenSubscriber implements EventSubscriberInterface
{
    private $set;
    private $tokenStorage;

    public function __construct(SetInterface $set, TokenStorageInterface $tokenStorage)
    {
        $this->set = $set;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelController(ControllerEvent $event): bool|Response
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getId();

        if ($event->getRequest()->headers->has('Authorization')) {
            $tokenWithBearer = $event->getRequest()->headers->get('Authorization');

            if ($this->set->contains('user.'.$userId.'.accessToken', $tokenWithBearer)) {
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
