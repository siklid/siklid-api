<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Http\Request;
use App\Foundation\Security\Token\TokenManagerInterface;
use App\Siklid\Application\Auth\Request\DeleteRefreshTokenRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Siklid\Application\Contract\Entity\UserInterface as SiklidUserInterface;


final class Logout extends AbstractAction
{
    private TokenManagerInterface $tokenManager;

    private TokenStorageInterface $token;

    private DeleteRefreshTokenRequest $deleteRefreshTokenRequest;

    private Request $request;

    public function __construct(
        TokenManagerInterface $tokenManager,
        TokenStorageInterface $token,
        DeleteRefreshTokenRequest $deleteRefreshTokenRequest,
        Request $request,
    ) {
        $this->tokenManager = $tokenManager;
        $this->token = $token;
        $this->deleteRefreshTokenRequest = $deleteRefreshTokenRequest;
        $this->request = $request;
    }

    /**
     * Executes action.
     */
    public function execute(): bool
    {
        $this->tokenManager->deleteRefreshToken($this->deleteRefreshTokenRequest);

        $tokenWithBearer = (string)$this->request->request()->headers->get('Authorization');

        $userToken = $this->token->getToken();

        assert($userToken instanceof JWTPostAuthenticationToken);

        $user = $userToken->getUser();
        assert($user instanceof SiklidUserInterface);
        $this->tokenManager->revokeAccessToken($user, $tokenWithBearer);

        return true;
    }
}
