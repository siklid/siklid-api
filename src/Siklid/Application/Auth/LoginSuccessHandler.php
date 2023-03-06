<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Security\Authentication\TokenManagerInterface;
use App\Siklid\Document\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Authentication success handler.
 */
final class LoginSuccessHandler extends ApiController implements AuthenticationSuccessHandlerInterface
{
    private TokenManagerInterface $tokenManager;
    private SerializerInterface $serializer;

    public function __construct(TokenManagerInterface $tokenManager, SerializerInterface $serializer)
    {
        $this->tokenManager = $tokenManager;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $data = $this->getResponseData($token);

        $groups = ['user:read', 'token:read'];
        assert(method_exists($this->serializer, 'normalize'));
        $data = (array)$this->serializer->normalize($data, 'json', ['groups' => $groups]);

        return $this->ok($data);
    }

    public function getResponseData(TokenInterface $token): array
    {
        /** @var User $user */
        $user = $token->getUser();
        $accessToken = $this->tokenManager->createAccessToken($user);
        $user->setAccessToken($accessToken);

        return [
            'user' => $user,
            'token' => $user->getAccessToken(),
        ];
    }
}
