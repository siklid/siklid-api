<?php

declare(strict_types=1);

namespace App\Tests\Unit\Foundation\Util;

use App\Foundation\Util\Json;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class JsonTest extends TestCase
{
    private Json $sut;

    /**
     * @return array[]
     */
    public function array_to_json_encodes_array_into_json_data_provider(): array
    {
        return [
            'empty array' => [
                'array' => [],
                'expected' => '[]',
            ],
            'array containing empty array' => [
                'array' => [[]],
                'expected' => '[[]]',
            ],
            'array with one element' => [
                'array' => ['foo' => 'bar'],
                'expected' => '{"foo":"bar"}',
            ],
            'array with multiple elements' => [
                'array' => ['foo' => 'bar', 'baz' => 'qux'],
                'expected' => '{"foo":"bar","baz":"qux"}',
            ],
        ];
    }

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
    public function json_to_array_returns_empty_array_if_json_is_invalid(): void
    {
        $json = 'invalid json';
        $expected = [];

        $actual = $this->sut->jsonToArray($json);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     *
     * @dataProvider array_to_json_encodes_array_into_json_data_provider
     */
    public function array_to_json_returns_a_valid_json(array $array, string $expected): void
    {
        $actual = $this->sut->arrayToJson($array);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function array_to_json_returns_empty_array_json_on_failure(): void
    {
        $array = ['foo' => fopen('php://memory', 'rb')];
        $expected = '[]';

        $actual = $this->sut->arrayToJson($array);

        $this->assertSame($expected, $actual);
    }
}
