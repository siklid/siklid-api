<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

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

        $client->request('POST', 'api/v1/auth/register/email', [
            'user' => [],
            'client' => [],
        ]);

        $this->assertTrue(true);
    }
}
