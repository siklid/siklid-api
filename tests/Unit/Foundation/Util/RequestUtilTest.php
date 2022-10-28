<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Util;

use App\Foundation\Util\Json;
use App\Foundation\Util\RequestUtil;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor - we don't need constructor
 */
class RequestUtilTest extends TestCase
{
    /**
     * @test
     */
    public function get_json(): void
    {
        $json = new Json();
        $sut = new RequestUtil($json);
        $this->assertSame($json, $sut->json());
    }
}
