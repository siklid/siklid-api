<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Http\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureHandler extends ApiController implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $body = [
            'message' => $exception->getMessageKey(),
            'errors' => [
                'email' => 'Invalid email or password.',
                'password' => 'Invalid email or password.',
            ],
        ];

        return $this->badRequest($body);
    }
}
