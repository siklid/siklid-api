<?php

declare(strict_types=1);

namespace App\Tests\Integration\Foundation\Pagination;

use App\Foundation\Pagination\CursorPaginator;
use App\Siklid\Document\User;
use App\Tests\Concern\CreatesKernel;
use App\Tests\Concern\Factory\UserFactoryTrait;
use App\Tests\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @psalm-suppress MissingConstructor
 */
class CursorPaginatorTest extends TestCase
{
    use CreatesKernel;
    use UserFactoryTrait;

    /**
     * @var User[]
     */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $documentManager = $this->getDocumentManager();
        for ($i = 0; $i < 3; ++$i) {
            $user = $this->makeUser();
            $documentManager->persist($user);
            $this->users[] = $user;
        }
        $documentManager->flush();
    }

    /**
     * @test
     */
    public function paginate_limit(): void
    {
        $sut = $this->createSut();
        $documentManager = $this->getDocumentManager();
        $builder = $documentManager->createQueryBuilder(User::class);

        $actual = $sut->paginate($builder, '', 1);

        $data = $actual->getData();
        $this->assertCount(1, $data);
        $this->assertSame($this->users[0], $data[0]);
    }

    /**
     * @test
     */
    public function paginate_after(): void
    {
        $sut = $this->createSut();
        $documentManager = $this->getDocumentManager();
        $builder = $documentManager->createQueryBuilder(User::class);
        $builder->sort('id', 'desc');
        $actual = $sut->paginate($builder, $this->users[2]->getId(), 1);

        $data = $actual->getData();
        $this->assertCount(1, $data);
        $this->assertSame($this->users[1], $data[0]);
    }

    /**
     * @test
     */
    public function paginate_can_get_after_offset_from_request(): void
    {
        $sut = $this->createSut(['after' => $this->users[2]->getId()]);
        $documentManager = $this->getDocumentManager();
        $builder = $documentManager->createQueryBuilder(User::class);
        $builder->sort('id', 'desc');
        $actual = $sut->paginate($builder, '', 1);

        $data = $actual->getData();
        $this->assertCount(1, $data);
        $this->assertSame($this->users[1], $data[0]);
    }

    /**
     * @test
     */
    public function page_links(): void
    {
        $sut = $this->createSut();
        $documentManager = $this->getDocumentManager();
        $builder = $documentManager->createQueryBuilder(User::class);
        $actual = $sut->paginate($builder, '', 1);

        $links = $actual->getLinks();
        $this->assertArrayHasKey('next', $links);
        $this->assertArrayHasKey('self', $links);
        $this->assertSame('https://localhost?after='.$this->users[0]->getId(), $links['next']);
        $this->assertSame('https://localhost?after=', $links['self']);
    }

    /**
     * @test
     */
    public function page_meta(): void
    {
        $sut = $this->createSut();
        $documentManager = $this->getDocumentManager();
        $builder = $documentManager->createQueryBuilder(User::class);
        $actual = $sut->paginate($builder, '', 7);

        $meta = $actual->getMeta();
        $this->assertSame(count($this->users), $meta['count']);
        $this->assertSame(7, $meta['perPage']);
    }

    /**
     * @param array<string, string> $params
     *
     * @psalm-suppress InvalidPropertyAssignmentValue - $request->query has the right type.
     */
    private function createSut(array $params = []): CursorPaginator
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getUri')->willReturn('https://localhost?after=');
        $request->expects($this->once())->method('getUriForPath')->willReturn('https://localhost');
        $request->query = new InputBag();
        foreach ($params as $key => $value) {
            $request->query->set($key, $value);
        }

        return CursorPaginator::create($request);
    }
}
