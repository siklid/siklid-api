<?php

declare(strict_types=1);

namespace App\Tests\Feature\Flashcard;

use App\Tests\Concern\Factory\FlashcardFactoryTrait;
use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;

class DeleteFlashCardTest extends TestCase
{
    use WebTestCaseTrait;
    use UserFactoryTrait;
    use FlashcardFactoryTrait;

    /** @test */
    public function delete_flashcard(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $flashcard = $this->makeFlashcard(['user' => $user]);
        $this->persistDocument($flashcard);
        $client->loginUser($user);

        $client->request('DELETE', 'api/v1/flashcards/'.$flashcard->getId());

        $this->assertResponseIsOk();
        $this->assertSoftDeleted($flashcard);
    }

    /** @test */
    public function delete_with_invalid_flashcard_id(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $client->loginUser($user);

        $client->request('DELETE', 'api/v1/flashcards/invalid-id');

        $this->assertResponseIsNotFound();
    }
}
