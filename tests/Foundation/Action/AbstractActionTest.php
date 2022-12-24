<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Action;

use App\Foundation\Action\AbstractAction;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class AbstractActionTest extends TestCase
{
    /**
     * @test
     */
    public function fill(): void
    {
        $sut = $this->getMockForAbstractClass(AbstractAction::class);
        $data = [
            'foo' => 'bar',
            'bar' => 'baz',
            'noSetterProperty' => 'foo',
        ];

        $result = $sut->fill(FillableClass::class, $data);

        $this->assertInstanceOf(FillableClass::class, $result);
        $this->assertSame('bar', $result->getFoo());
        $this->assertSame('baz', $result->getBar());
    }
}

/**
 * @psalm-suppress MissingConstructor
 */
class FillableClass
{
    private string $foo;

    private string $bar;

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function setFoo(string $foo): FillableClass
    {
        $this->foo = $foo;

        return $this;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): FillableClass
    {
        $this->bar = $bar;

        return $this;
    }
}
