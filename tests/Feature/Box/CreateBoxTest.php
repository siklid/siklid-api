<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Application\Contract\Type\RepetitionAlgorithm;
use App\Siklid\Document\Box;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class CreateBoxTest extends TestCase
{
    use WebTestCaseTrait;

    /**
     * @test
     */
    public function user_can_create_a_box(): void
    {
        $client = $this->makeClient();
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
        $this->assertResponseJsonStructure([
            'data' => [
                'id',
                'name',
                'repetitionAlgorithm',
                'description',
                'hashtags',
                'createdAt',
            ],
        ]);
        $this->assertExists(Box::class, [
            'name' => $name,
            'description' => $description,
            'user' => $user,
        ]);
        $this->assertSame($name, $this->getFromResponse('data.name'));
        $this->assertSame($description, $this->getFromResponse('data.description'));
        $this->assertEquals(
            RepetitionAlgorithm::Leitner,
            RepetitionAlgorithm::coerce($this->getFromResponse('data.repetitionAlgorithm'))
        );
    }

    /**
     * @test
     */
    public function box_name_is_required(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);
        $description = $this->faker->sentence();

        $client->request('POST', '/api/v1/boxes', [
            'description' => $description,
        ]);

        $this->assertResponseHasValidationError();
        $this->assertResponseJsonStructure([
            'message',
            'errors' => ['name' => []],
        ]);
        $this->assertNotExists(Box::class, [
            'description' => $description,
            'user' => $user,
        ]);
    }

    /**
     * @test
     */
    public function box_description_is_optional(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);
        $name = $this->faker->word();

        $client->request('POST', '/api/v1/boxes', [
            'name' => $name,
        ]);

        $this->assertResponseIsCreated();
        $this->assertExists(Box::class, [
            'name' => $name,
            'user' => $user,
        ]);
        $actual = $this->getFromResponse('data.description');
        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function box_hashtags_are_extracted_from_the_box_description(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);
        $name = $this->faker->word();
        $description = $this->faker->sentence().' #hashtag1 '.$this->faker->sentence().' #hashtag2';

        $client->request('POST', '/api/v1/boxes', [
            'name' => $name,
            'description' => $description,
        ]);

        $this->assertResponseIsCreated();
        $this->assertExists(Box::class, [
            'name' => $name,
            'description' => $description,
            'user' => $user,
            'hashtags' => ['#hashtag1', '#hashtag2'],
        ]);

        $actual = (array)$this->getFromResponse('data.hashtags');
        $this->assertEquals(['#hashtag1', '#hashtag2'], $actual);
    }
}
