<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Document;

use App\Foundation\Exception\LogicException;
use App\Siklid\Document\Box;
use App\Tests\TestCase;
use DateInterval;
use Lcobucci\Clock\Clock;
use Lcobucci\Clock\FrozenClock;

/**
 * @psalm-suppress MissingConstructor
 */
class BoxTest extends TestCase
{
    private Clock $clock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clock = FrozenClock::fromUTC();
    }

    /**
     * @test
     */
    public function delete(): Box
    {
        $sut = new Box($this->clock);
        $sut->setDeletedAt(null);

        $sut->delete();

        $this->assertNotNull($sut->getDeletedAt());
        $this->assertEquals($this->clock->now(), $sut->getDeletedAt());

        return $sut;
    }

    /**
     * @test
     *
     * @depends delete
     */
    public function it_should_not_delete_already_deleted_box(Box $sut): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Box is already deleted');

        $sut->delete();
    }

    /**
     * @test
     *
     * @dataProvider provideIsDeleted
     */
    public function is_deleted(Box $sut, bool $expected): void
    {
        $this->assertSame($expected, $sut->isDeleted());
    }

    /**
     * @test
     */
    public function touch(): void
    {
        $sut = new Box($this->clock);
        $yesterday = $this->clock->now()->sub(new DateInterval('P1D'));
        $sut->setUpdatedAt($yesterday);

        $sut->touch();

        $this->assertEquals($this->clock->now(), $sut->getUpdatedAt());
    }

    /**
     * @return array[]
     */
    public function provideIsDeleted(): array
    {
        $deletedBox = new Box();
        $deletedBox->delete();

        return [
            'deleted' => [
                'sut' => $deletedBox,
                'expected' => true,
            ],
            'not deleted' => [
                'sut' => new Box(),
                'expected' => false,
            ],
        ];
    }
}
