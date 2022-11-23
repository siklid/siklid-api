<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Document\Box;
use App\Siklid\Document\User;
use App\Tests\Concern\BoxFactoryTrait;
use App\Tests\FeatureTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class ListBoxTest extends FeatureTestCase
{
    use BoxFactoryTrait;

    /**
     * @test
     */
    public function guest_can_paginate_all_boxes(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        for ($i = 0; $i < 26; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
        }

        $client->request('GET', '/api/v1/boxes');

        $this->assertResponseIsOk();
        $this->assertResponseIsJson();
        $this->assertResponseJsonStructure($client, [
            'data' => [
                [
                    'id',
                    'name',
                    'repetitionAlgorithm',
                    'description',
                    'hashtags',
                    'user' => [
                        'id',
                        'username',
                    ],
                ],
            ],
            'links' => ['self', 'next'],
            'meta' => ['count'],
        ]);
        $data = $this->getFromResponse($client, 'data');
        $this->assertIsArray($data);
        $this->assertCount(25, $data);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedArrayAccess
     */
    public function after_cursor_paginates_boxes(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $boxes = [];
        for ($i = 0; $i < 26; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
            $boxes[] = $box;
        }
        $cursor = $boxes[1]->getId();

        $client->request('GET', "/api/v1/boxes?after=$cursor");

        $this->assertResponseIsOk();
        $data = $this->getFromResponse($client, 'data');
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame($boxes[0]->getId(), $data[0]['id'], 'Last box returned first');
    }

    /**
     * @test
     *
     * @psalm-suppress MixedArrayAccess
     */
    public function guest_can_paginate_boxes_by_hashtag(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $box = $this->makeBox(['user' => $user, 'hashtags' => ['#foo', '#not_bar']]);
        $this->persistDocument($box);
        $this->persistDocument($this->makeBox(['user' => $user, 'hashtags' => ['#bar']]));

        $client->request('GET', '/api/v1/boxes?hashtag=foo');

        $this->assertResponseIsOk();
        $data = $this->getFromResponse($client, 'data');
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame($box->getId(), $data[0]['id']);
    }

    /**
     * @test
     */
    public function empty_hashtag_filter_returns_all_boxes(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        $this->persistDocument($this->makeBox(['user' => $user, 'hashtags' => ['#foo', '#not_bar']]));
        $this->persistDocument($this->makeBox(['user' => $user, 'hashtags' => ['#bar']]));

        $client->request('GET', '/api/v1/boxes?hashtag=');

        $this->assertResponseIsOk();
        $data = $this->getFromResponse($client, 'data');
        $this->assertIsArray($data);
        $this->assertCount(2, $data);
    }

    /**
     * @test
     */
    public function pagination_size_can_be_specified_with_a_query_param(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        for ($i = 0; $i < 5; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
        }

        $client->request('GET', '/api/v1/boxes?size=3');

        $this->assertResponseIsOk();
        $data = $this->getFromResponse($client, 'data');
        $this->assertIsArray($data);
        $this->assertCount(3, $data);
    }

    /**
     * @test
     */
    public function pagination_size_min_size_is_one(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        for ($i = 0; $i < 5; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
        }

        $client->request('GET', '/api/v1/boxes?size=0');

        $this->assertResponseHasValidationError();
        $this->assertResponseIsJson();
        $content = (string)$client->getResponse()->getContent();
        $this->assertStringContainsString('Size must be 1 or greater.', $content);
    }

    /**
     * @test
     */
    public function max_pagination_size_is_100(): void
    {
        $client = $this->createCrawler();
        $user = $this->makeUser();
        $this->persistDocument($user);
        for ($i = 0; $i < 5; ++$i) {
            $box = $this->makeBox(['user' => $user]);
            $this->persistDocument($box);
        }

        $client->request('GET', '/api/v1/boxes?size=101');

        $this->assertResponseHasValidationError();
        $this->assertResponseIsJson();
        $content = (string)$client->getResponse()->getContent();
        $this->assertStringContainsString('Size must be less than or equal to 100.', $content);
    }

    protected function tearDown(): void
    {
        $this->dropCollection(User::class);
        $this->dropCollection(Box::class);

        parent::tearDown();
    }
}
