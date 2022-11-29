<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Validation\Constraint;

use App\Foundation\Validation\Constraint\UniqueDocument;
use App\Tests\TestCase;

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
