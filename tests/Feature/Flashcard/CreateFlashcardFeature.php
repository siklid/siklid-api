<?php

declare(strict_types=1);

namespace App\Tests\Feature\Flashcard;

use App\Siklid\Document\Flashcard;
use App\Tests\Concern\Factory\BoxFactoryTrait;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

final class CreateFlashcardFeature extends TestCase
{
    use WebTestCaseTrait;
    use BoxFactoryTrait;

    /**
     * @test
     */
    public function flashcard_backside_and_boxes_are_required(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $box = $this->makeBox(['user' => $user]);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($user);

        $client->request('POST', '/api/v1/flashcards');

        $this->assertResponseHasValidationError('back', 'This field is missing.');
        $this->assertResponseHasValidationError('boxes', 'This field is missing.');
    }

    /**
     * @test
     */
    public function boxes_field_should_be_an_array(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $box = $this->makeBox(['user' => $user]);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($user);

        $client->request('POST', '/api/v1/flashcards', [
            'boxes' => 'not an array',
        ]);

        $this->assertArrayHasKey('boxes', (array)$this->getResponseJsonData('errors'));
    }

    /**
     * @test
     */
    public function boxes_field_should_contain_at_least_single_box_id(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $box = $this->makeBox(['user' => $user]);
        $this->persistDocument($user);
        $this->persistDocument($box);
        $client->loginUser($user);

        $client->request('POST', '/api/v1/flashcards', [
            'boxes' => [],
        ]);

        $this->assertResponseHasValidationError('boxes', 'This collection should contain 1 element or more.');
    }

    /**
     * @test
     */
    public function user_can_create_a_flashcard(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $boxes = [];
        for ($i = 0; $i < 3; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
            $boxes[] = $box;
        }
        $client->loginUser($user);
        $back = $this->faker->sentence();
        $front = $this->faker->sentence();

        $client->request('POST', '/api/v1/flashcards', [
            'back' => $back,
            'front' => $front,
            'boxes' => [
                $boxes[0]->getId(),
                $boxes[1]->getId(),
                $boxes[2]->getId(),
            ],
        ]);

        $this->assertResponseIsCreated();
        $this->assertResponseJsonStructure([
            'data' => [
                'id',
                'back',
                'front',
                'boxes',
                'user',
            ],
        ]);
        $this->assertExists(Flashcard::class, [
            'back' => $back,
            'front' => $front,
            'user' => $user,
        ]);
        $this->assertSame($back, $this->getResponseJsonData('data.back'));
        $this->assertSame($front, $this->getResponseJsonData('data.front'));
        $this->assertSame($user->getId(), $this->getResponseJsonData('data.user.id'));
        $this->assertCount(3, (array)$this->getResponseJsonData('data.boxes'));
        $this->assertSame($boxes[0]->getId(), $this->getResponseJsonData('data.boxes.0.id'));
        $this->assertSame($boxes[1]->getId(), $this->getResponseJsonData('data.boxes.1.id'));
        $this->assertSame($boxes[2]->getId(), $this->getResponseJsonData('data.boxes.2.id'));
    }

    /**
     * @test
     */
    public function user_can_only_assign_flashcard_to_her_boxes(): void
    {
        $client = $this->makeClient();
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();
        $this->persistDocument($user1);
        $this->persistDocument($user2);
        $box = $this->makeBox(['user' => $user1]);
        $this->persistDocument($box);
        $client->loginUser($user2);

        $client->request('POST', '/api/v1/flashcards', [
            'back' => $this->faker->sentence(),
            'boxes' => [
                $box->getId(),
            ],
        ]);

        $this->assertResponseHasValidationError('boxes', 'This collection should contain 1 element or more.');
    }
}
