<?php

declare(strict_types=1);

namespace App\Tests\Unit\Siklid\Document;

use App\Foundation\Exception\LogicException;
use App\Siklid\Document\Box;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class BoxTest extends TestCase
{
    /**
     * @test
     */
    public function delete(): Box
    {
        $sut = new Box();

        $sut->delete();

        $this->assertNotNull($sut->getDeletedAt());

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
        $this->expectErrorMessage('Box is already deleted');

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
        $sut = new Box();

        $sut->touch();

        $this->assertNotNull($sut->getUpdatedAt());
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
