<?php

declare(strict_types=1);

namespace App\Tests\Foundation\ValueObject;

use App\Foundation\ValueObject\Username;
use App\Tests\Concern\Util\WithFaker;
use App\Tests\TestCase;
use Error;
use JsonException;

/**
 * @psalm-suppress MissingConstructor
 */
class UsernameTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     *
     * @psalm-suppress InaccessibleMethod
     */
    public function it_is_not_instantiable_by_public_constructor(): void
    {
        $this->expectException(Error::class);

        $sut = Username::class;
        new $sut('foo', 'foo');
    }

    /**
     * @test
     *
     * @dataProvider fromStringDataProvider
     */
    public function from_string_to_string(string $expected, string $original): void
    {
        $this->assertSame($expected, (string)Username::fromString($original));
    }

    /**
     * @test
     */
    public function original(): void
    {
        $this->assertSame('Foo Bar', Username::fromString('Foo Bar')->original());
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function json_serialize(): void
    {
        $this->assertSame('"Foo.Bar"', json_encode(Username::fromString('Foo Bar'), JSON_THROW_ON_ERROR));
    }

    /**
     * @return string[][]
     */
    public function fromStringDataProvider(): array
    {
        return [
            ['Jayde92', 'Jayde92'],
            ['Jayde.92', 'Jayde 92'],
            ['Jayde.92', 'Jayde.92'],
            ['Jayde.92', 'Jayde. 92'],
            ['Jayde.92', 'Jayde.  92'],
            ['imdhemy', '@imdhemy'],
        ];
    }

    /**
     * @test
     */
    public function equals(): void
    {
        $value = $this->faker->userName();
        $obj1 = Username::fromString($value);
        $obj2 = Username::fromString($value);

        $this->assertTrue($obj1->equals($obj2));
    }
}
