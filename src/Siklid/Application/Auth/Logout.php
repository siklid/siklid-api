<?php

declare(strict_types=1);

namespace App\Siklid\Application\Auth;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * This handler is run on logout.
 * It removes all refresh tokens for the authenticated user. This prevents the usage of old
 * refresh tokens by an attacker. As there is no repository for refresh token we do it the
 * good old way and use the database connection directy.
 */
final class LogoutHandler implements LogoutHandlerInterface
{
    /** @var Connection */
    private $databaseConnection;

    public function __construct(Connection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function logout(Request $request, Response $response, TokenInterface $token): void
    {
        $authenticatedUser = $token->getUser();

        if (null === $authenticatedUser) {
            return;
        }

        /* @var User $authenticatedUser */
        /* @noinspection PhpUnhandledExceptionInspection */
        // Possible exception should not be caught, as we need to become aware that something broke here
        $this->databaseConnection->exec(sprintf('
            DELETE FROM refresh_tokens
            WHERE username = "%s"
        ', $authenticatedUser->getUsername()));
    }
}