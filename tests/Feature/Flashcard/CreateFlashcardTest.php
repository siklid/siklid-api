<?php

declare(strict_types=1);

namespace App\Tests\Feature\Flashcard;

use App\Tests\Concern\Factory\BoxFactoryTrait;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

class CreateFlashcardTest extends TestCase
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

        $client->request('POST', '/api/v1/flashcards', []);

        $this->assertResponseHasValidationError('backside', 'This field is missing.');
        $this->assertResponseHasValidationError('boxes', 'This field is missing.');
    }
}
