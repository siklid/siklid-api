<?php

declare(strict_types=1);

namespace App\Tests\Feature\Flashcard;

use App\Tests\Concern\Factory\BoxFactoryTrait;
use App\Tests\Concern\Factory\FlashcardFactoryTrait;
use App\Tests\Concern\WebTestCaseTrait;
use App\Tests\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class ViewFlashcardTest extends TestCase
{
    use WebTestCaseTrait;
    use FlashcardFactoryTrait;
    use BoxFactoryTrait;

    /**
     * @test
     */
    public function user_can_show_single_flashcard(): void
    {
        $client = $this->makeClient();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $box = $this->makeBox(['user' => $user]);
        $this->persistDocument($box);

        $back = $this->faker->sentence();
        $front = $this->faker->sentence();
        $flashcard = $this->makeFlashcard(['front' => $front, 'back' => $back, 'user' => $user]);
        $flashcard->setBoxes(new ArrayCollection([$box]));
        $this->persistDocument($flashcard);

        $client->loginUser($user);

        $client->request('GET', '/api/v1/flashcards/'.$flashcard->getId());

        $this->assertResponseIsOk();
        $this->assertResponseIsJson();
        $this->assertSame($back, $this->getResponseJsonData('data.back'));
        $this->assertSame($front, $this->getResponseJsonData('data.front'));
        $this->assertSame($user->getId(), $this->getResponseJsonData('data.user.id'));
    }
}
