<?php

namespace App\Tests\Unit\Foundation\Util;

use App\Foundation\Util\Json;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class JsonTest extends TestCase
{
    private Json $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new Json();
    }

    /**
     * @test
     */
    public function json_to_array_decodes_valid_json_into_an_array(): void
    {
        $json = '{"foo":"bar"}';
        $expected = ['foo' => 'bar'];

        $actual = $this->sut->jsonToArray($json);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function json_to_array_returns_empty_if_json_is_invalid(): void
    {
        $json = 'invalid json';
        $expected = [];

        $actual = $this->sut->jsonToArray($json);

        $this->assertSame($expected, $actual);
    }
}
