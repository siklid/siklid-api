<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Document\Box;
use App\Tests\Concern\Factory\BoxFactoryTrait;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class DeleteBoxFeature extends TestCase
{
    use WebTestCaseTrait;
    use BoxFactoryTrait;

    /**
     * @test
     */
    public function user_can_delete_a_box(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $box = $this->makeBox();
        $box->setUser($user);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($user);

        $client->request('DELETE', 'api/v1/boxes/'.$box->getId());

        $this->assertResponseIsOk();
        $this->assertNotNull($box->getDeletedAt());
        $this->assertExists(Box::class, ['id' => $box->getId()]);
    }

    /**
     * @test
     */
    public function box_visitor_can_not_delete_it(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $visitor = $this->makeUser();
        $box = $this->makeBox();
        $box->setUser($user);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($visitor);

        $client->request('DELETE', 'api/v1/boxes/'.$box->getId());

        $this->assertResponseIsForbidden();
    }
}
