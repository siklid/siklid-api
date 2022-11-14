<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Document\Box;
use App\Siklid\Document\User;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class CreateBoxTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function user_can_create_a_box(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);
        $name = $this->faker->word();
        $description = $this->faker->sentence();

        $client->request('POST', '/api/v1/boxes', [
            'name' => $name,
            'description' => $description,
        ]);

        $this->assertResponseIsCreated();
        $this->assertResponseJsonStructure($client, [
            'data' => [
                'id',
                'name',
                'description',
                'createdAt',
                'updatedAt',
            ],
        ]);
        $this->assertExists(Box::class, [
            'name' => $name,
            'description' => $description,
            'user' => $user,
        ]);

        $this->deleteDocument(User::class, ['id' => $user->getId()]);
        $this->deleteDocument(Box::class, ['id' => $this->getFromResponse($client, 'data.id')]);
    }

    /**
     * @test
     */
    public function box_name_is_required(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);
        $description = $this->faker->sentence();

        $client->request('POST', '/api/v1/boxes', [
            'description' => $description,
        ]);

        $this->assertResponseHasValidationError();
        $this->assertResponseJsonStructure($client, [
            'message',
            'errors' => ['name' => []],
        ]);
        $this->assertNotExists(Box::class, [
            'description' => $description,
            'user' => $user,
        ]);
    }
}
