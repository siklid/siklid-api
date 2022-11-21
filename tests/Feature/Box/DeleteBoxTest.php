<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Document\Box;
use App\Tests\Concerns\BoxFactoryTrait;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class DeleteBoxTest extends FeatureTestCase
{
    use BoxFactoryTrait;

    /**
     * @test
     */
    public function user_can_delete_a_box(): Box
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $box = $this->makeBox();
        $box->setUser($user);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($user);

        $client->request('DELETE', 'api/v1/boxes/'.$box->getId());

        $this->assertResponseIsOk();

        $this->deleteDocument($user);

        return $box;
    }

    /**
     * @test
     *
     * @depends user_can_delete_a_box
     */
    public function it_should_be_a_soft_delete(Box $box): void
    {
        $this->assertNotNull($box->getDeletedAt());
        $this->assertExists(Box::class, ['id' => $box->getId()]);
        $this->assertNotExists(Box::class, ['id' => $box->getId(), 'deletedAt' => null]);

        $this->deleteDocument(Box::class, ['id' => $box->getId()]);
    }

    /**
     * @test
     */
    public function box_visitor_can_not_delete_it(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $visitor = $this->makeUser();
        $box = $this->makeBox();
        $box->setUser($user);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($visitor);

        $client->request('DELETE', 'api/v1/boxes/'.$box->getId());

        $this->assertResponseIsForbidden();

        $this->deleteDocument($user);
        $this->deleteDocument($box);
    }
}
