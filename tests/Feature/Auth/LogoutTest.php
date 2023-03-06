<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Foundation\Security\Authentication\TokenManagerInterface;
use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\Concern\Util\WithJson;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

class LogoutTest extends TestCase
{
    use WebTestCaseTrait;
    use UserFactoryTrait;
    use WithJson;

    /**
     * @test
     */
    public function logout(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $tokenManager = $this->container()->get(TokenManagerInterface::class);
        $accessToken = $tokenManager->createAccessToken($user);
        $refreshToken = (string)$accessToken->getRefreshToken();

        $client->request('POST', '/api/v1/auth/logout', ['refreshToken' => $refreshToken], [], [
            'HTTP_Authorization' => 'Bearer '.$accessToken->getToken(),
        ]);

        $this->assertTrue($tokenManager->isAccessTokenRevokedForUser((string)$accessToken, $user));
        $this->assertResponseIsOk();
        $this->assertResponseJsonStructure(['data' => ['message']]);
        $this->assertSame('You have been logged out.', $this->getResponseJsonData('data.message'));
    }

    /**
     * @test
     */
    public function using_same_access_token_after_logout(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $tokenManager = $this->container()->get(TokenManagerInterface::class);
        $accessToken = $tokenManager->createAccessToken($user);
        $refreshToken = (string)$accessToken->getRefreshToken();
        $tokenManager->revokeAccessTokenForUser((string)$accessToken, $user);

        $client->request('POST', '/api/v1/auth/logout', ['refreshToken' => $refreshToken], [], [
            'HTTP_Authorization' => 'Bearer '.$accessToken->getToken(),
        ]);

        $this->assertResponseIsUnAuthorized();
    }
}
