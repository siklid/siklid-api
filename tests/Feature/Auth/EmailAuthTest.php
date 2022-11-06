<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Foundation\ValueObject\Email;
use App\Foundation\ValueObject\Username;
use App\Siklid\Application\Auth\LoginFailureHandler;
use App\Siklid\Application\Auth\LoginSuccessHandler;
use App\Siklid\Document\User;
use App\Tests\FeatureTestCase;
use Symfony\Component\Yaml\Yaml;

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

        $this->assertExists(User::class, [
            'email' => $email,
            'username' => $username,
        ]);

        $this->deleteDocument(User::class, [
            'email' => $email,
            'username' => $username,
        ]);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedArrayAccess
     */
    public function json_login_is_configured(): void
    {
        /** @var array $securityConfig */
        $securityConfig = Yaml::parse(file_get_contents(__DIR__.'/../../../config/packages/security.yaml'));

        $expected = [
            'check_path' => 'api_login_check',
            'success_handler' => LoginSuccessHandler::class,
            'failure_handler' => LoginFailureHandler::class,
            'username_path' => 'email',
            'password_path' => 'password',
        ];

        $this->assertSame($expected, $securityConfig['security']['firewalls']['api']['json_login']);
    }
}
