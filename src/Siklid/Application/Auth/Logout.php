<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Foundation\Action\AbstractAction;
use App\Foundation\Exception\LogicException;
use App\Foundation\Security\Token\TokenManagerInterface as TokenManager;
use App\Siklid\Application\Auth\Request\LogoutRequest;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Security\UserResolverInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;

final class Logout extends AbstractAction
{
    private TokenManager $tokenManager;
    private LogoutRequest $request;
    private UserResolverInterface $userResolver;
    private DocumentManager $dm;

    public function __construct(
        TokenManager $tokenManager,
        LogoutRequest $request,
        UserResolverInterface $userResolver,
        DocumentManager $dm
    ) {
        $this->tokenManager = $tokenManager;
        $this->request = $request;
        $this->userResolver = $userResolver;
        $this->dm = $dm;
    }

    /**
     * Executes action.
     */
    public function execute(): bool
    {
        $this->revokeRefreshToken();
        $this->revokeAccessToken();

        return true;
    }

    public function revokeAccessToken(): void
    {
        $user = $this->userResolver->getUser();
        assert($user instanceof UserInterface);
        $accessToken = $this->request->getAccessToken();
        $this->tokenManager->revokeAccessTokenForUser($accessToken, $user);
    }

    public function revokeRefreshToken(): void
    {
        $refreshTokenRepository = $this->dm->getRepository(RefreshToken::class);
        $refreshToken = $refreshTokenRepository->findOneBy(['refreshToken' => $this->request->refreshToken()]);
        assert($refreshToken instanceof RefreshToken, new LogicException('Refresh token not found'));
        $this->tokenManager->revokeRefreshToken($refreshToken);
    }
}
