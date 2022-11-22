<?php

declare(strict_types=1);

namespace App\Tests\Feature\Box;

use App\Siklid\Document\Box;
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

        $this->deleteDocument($user);
        $this->deleteAllDocuments(Box::class);
    }

    /**
     * @test
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

        $this->deleteDocument($user);
        $this->deleteAllDocuments(Box::class);
    }
}
