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
class CreateBoxFeature extends TestCase
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
        $this->assertSame($name, $this->getResponseJsonData('data.name'));
        $this->assertSame($description, $this->getResponseJsonData('data.description'));
        $this->assertEquals(
            RepetitionAlgorithm::Leitner,
            RepetitionAlgorithm::coerce($this->getResponseJsonData('data.repetitionAlgorithm'))
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

        $this->postJson($client, '/api/v1/boxes', [
            'description' => $description,
        ]);

        $this->assertResponseHasValidationError();
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
        $actual = $this->getResponseJsonData('data.description');
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
        $description = $this->faker->sentence().' #hashtag1 '.$this->faker->sentence(
        ).' #hashtag2 #hash-tag #123hashtag #هاشتاج '.$this->faker->sentence().' #1234 #hash_tag';

        $client->request('POST', '/api/v1/boxes', [
            'name' => $name,
            'description' => $description,
        ]);

        $this->assertResponseIsCreated();
        $this->assertExists(Box::class, [
            'name' => $name,
            'description' => $description,
            'user' => $user,
            'hashtags' => ['#hashtag1', '#hashtag2', '#hash', '#123hashtag', '#هاشتاج', '#1234', '#hash_tag'],
        ]);

        $actual = (array)$this->getResponseJsonData('data.hashtags');
        $this->assertEquals(['#hashtag1', '#hashtag2', '#hash', '#123hashtag', '#هاشتاج', '#1234', '#hash_tag'],
            $actual);
    }
}
