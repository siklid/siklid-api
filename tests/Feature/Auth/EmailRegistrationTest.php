<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Siklid\Document\User;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @group          legacy
 *
 * @see            {https://github.com/piscibus/siklid-api/issues/43}
 */
class EmailRegistrationTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function guest_can_register_by_email(): void
    {
        $client = $this->createCrawler();

        $email = $this->faker->unique()->email();
        $username = $this->faker->unique()->userName();

        $client->request('POST', 'api/v1/auth/register/email', [
            'user' => [
                'email' => $email,
                'username' => $username,
                'password' => $this->faker->password(),
            ],
        ]);

        $this->assertResponseIsCreated();
        $this->assertResponseIsJson();

        $this->assertResponseJsonStructure($client, [
            'data' => [
                'user' => ['id', 'email', 'username'],
                'token' => ['accessToken', 'expiresAt', 'tokenType', 'refreshToken'],
            ],
        ]);

        $this->assertExists(User::class, [
            'email' => $email,
            'username' => $username,
        ]);

        $this->deleteDocument(User::class, [
            'email' => $email,
            'username' => $username,
        ]);
    }
}
