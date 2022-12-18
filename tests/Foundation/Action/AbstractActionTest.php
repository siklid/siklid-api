<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Action;

use App\Foundation\Action\AbstractAction;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @psalm-suppress MissingConstructor
 */
class AbstractActionTest extends TestCase
{
    /**
     * @test
     */
    public function get_config(): AbstractAction
    {
        $sut = $this->getMockForAbstractClass(AbstractAction::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturn(true);
        $parameterBag = $this->createMock(ParameterBag::class);
        $parameterBag->method('get')->willReturn(['bar' => 'baz']);
        $container->method('get')->willReturn($parameterBag);
        $sut->setContainer($container);

        $this->assertSame('baz', $sut->getConfig('foo.bar'));
        $this->assertSame(['bar' => 'baz'], $sut->getConfig('foo'));
        $this->assertNull($sut->getConfig('foo.missing'));

        return $sut;
    }

    /**
     * @test
     *
     * @depends get_config
     */
    public function get_config_returns_null_by_default(AbstractAction $sut): void
    {
        $this->assertNull($sut->getConfig('foo.missing'));
    }

    /**
     * @test
     *
     * @depends get_config
     */
    public function get_config_can_accept_a_default_value(AbstractAction $sut): void
    {
        $this->assertSame('default', $sut->getConfig('foo.missing', 'default'));
    }

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
