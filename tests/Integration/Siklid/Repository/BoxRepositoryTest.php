<?php

declare(strict_types=1);

namespace App\Tests\Integration\Siklid\Repository;

use App\Siklid\Document\Box;
use App\Siklid\Repository\BoxRepository;
use App\Tests\Concern\BoxFactoryTrait;
use App\Tests\Concern\UserFactoryTrait;
use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class BoxRepositoryTest extends IntegrationTestCase
{
    use UserFactoryTrait;
    use BoxFactoryTrait;

    /**
     * @test
     *
     * @psalm-suppress MixedMethodCall
     */
    public function paginate_after_uses_the_after_cursor(): void
    {
        $user = $this->makeUser();
        $box1 = $this->makeBox(['user' => $user]);
        $box2 = $this->makeBox(['user' => $user]);
        $box3 = $this->makeBox(['user' => $user]);
        $this->persistDocument($user);
        $this->persistDocument($box1);
        $this->persistDocument($box2);
        $this->persistDocument($box3);

        /** @var BoxRepository $sut */
        $sut = $this->getRepository(Box::class);

        $page = $sut->paginateAfter($box2->getId(), null, 1);

        $this->assertCount(1, $page->getData());
        $this->assertSame($box1->getId(), $page->getData()[0]->getId());

        $this->deleteDocument($user);
        $this->deleteAllDocuments(Box::class);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedMethodCall
     */
    public function paginate_after_filters_by_hashtag(): void
    {
        $user = $this->makeUser();
        $box1 = $this->makeBox(['user' => $user, 'hashtags' => ['#foo']]);
        $box2 = $this->makeBox(['user' => $user, 'hashtags' => ['#bar']]);
        $box3 = $this->makeBox(['user' => $user, 'hashtags' => ['#foo']]);
        $this->persistDocument($user);
        $this->persistDocument($box1);
        $this->persistDocument($box2);
        $this->persistDocument($box3);

        /** @var BoxRepository $sut */
        $sut = $this->getRepository(Box::class);

        $page = $sut->paginateAfter('', '#foo', 1);

        $this->assertCount(1, $page->getData());
        $this->assertSame($box3->getId(), $page->getData()[0]->getId());

        $this->deleteDocument($user);
        $this->deleteAllDocuments(Box::class);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedMethodCall
     */
    public function paginate_after_limit(): void
    {
        $user = $this->makeUser();
        $box1 = $this->makeBox(['user' => $user]);
        $box2 = $this->makeBox(['user' => $user]);
        $box3 = $this->makeBox(['user' => $user]);
        $this->persistDocument($user);
        $this->persistDocument($box1);
        $this->persistDocument($box2);
        $this->persistDocument($box3);

        /** @var BoxRepository $sut */
        $sut = $this->getRepository(Box::class);

        $page = $sut->paginateAfter('', null, 1);

        $this->assertCount(1, $page->getData());
        $this->assertSame($box3->getId(), $page->getData()[0]->getId());

        $this->deleteDocument($user);
        $this->deleteAllDocuments(Box::class);
    }
}