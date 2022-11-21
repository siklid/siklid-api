<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Document\RefreshToken;
use App\Siklid\Document\User;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @group          legacy
 *
 * @see            {https://github.com/piscibus/siklid-api/issues/43}
 */
class EmailAuthTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function guest_can_register_by_email(): void
    {
        $client = $this->createCrawler();

        $email = Email::fromString($this->faker->unique()->email());
        $username = Username::fromString($this->faker->unique()->userName());

        $client->request('POST', 'api/v1/auth/register/email', [
            'email' => $email,
            'username' => $username,
            'password' => $this->faker->password(),
        ]);

        $this->assertResponseIsCreated();
        $this->assertResponseIsJson();
        $this->assertResponseJsonStructure($client, [
            'data' => [
                'user' => ['id', 'email', 'username'],
                'token' => ['accessToken', 'expiresAt', 'tokenType', 'refreshToken'],
            ],
        ]);
        $this->assertExists(User::class, ['email' => $email]);

        $this->deleteDocument(User::class, [
            'email' => $email,
            'username' => $username,
        ]);

        $this->deleteDocument(RefreshToken::class, ['username' => $email]);
    }

    /**
     * @test
     */
    public function guest_can_login_by_email(): void
    {
        $client = $this->createCrawler();
        $email = Email::fromString($this->faker->unique()->email());
        $password = $this->faker->password();
        $user = $this->makeUser(compact('email', 'password'));
        $this->persistDocument($user);

        $client->request(
            'POST',
            'api/v1/auth/login/email',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->json->arrayToJson([
                'email' => $email,
                'password' => $password,
            ])
        );

        $this->assertResponseIsOk();
        $this->assertResponseIsJson();
        $this->assertResponseJsonStructure($client, [
            'data' => [
                'user' => ['id', 'email', 'username'],
                'token' => ['accessToken', 'expiresAt', 'tokenType', 'refreshToken'],
            ],
        ]);

        $this->deleteDocument(User::class, ['email' => $email]);
        $this->deleteDocument(RefreshToken::class, ['username' => $user->getUserIdentifier()]);
    }
}
