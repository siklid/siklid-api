<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Http\ApiController;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Siklid\Document\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Authentication success handler.
 */
class LoginSuccessHandler extends ApiController implements AuthenticationSuccessHandlerInterface
{
    private TokenManagerInterface $tokenManager;

    public function __construct(TokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $data = $this->getResponseData($token);

        $groups = ['user:read', 'token:read'];

        return $this->ok($data, $groups);
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
