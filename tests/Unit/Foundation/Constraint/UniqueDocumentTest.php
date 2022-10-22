<?php

namespace App\Tests\Unit\Foundation\Constraint;

use App\Foundation\Constraint\UniqueDocument;
use PHPUnit\Framework\TestCase;

class UniqueDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_use_mongo_odm_service(): void
    {
        $sut = new UniqueDocument(['field']);

        self::assertSame('doctrine_odm.mongodb.unique', $sut->service);
    }
}
