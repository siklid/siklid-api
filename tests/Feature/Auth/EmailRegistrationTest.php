<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Siklid\Document\User;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
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
            ],
        ]);

        $this->assertExists(User::class, [
            'email' => $email,
            'username' => $username,
        ]);
    }
}
