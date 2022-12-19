<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Action;

use App\Foundation\Action\Config;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;

class ConfigTest extends TestCase
{
    use KernelTestCaseTrait;

    /**
     * @test
     */
    public function all(): void
    {
        $containerBag = $this->createMock(ContainerBag::class);
        $containerBag->expects($this->once())->method('all')->willReturn(['foo' => 'bar']);
        $sut = new Config($containerBag);

        $actual = $sut->all();

        $this->assertSame(['foo' => 'bar'], $actual);
    }

    /**
     * @test
     */
    public function has(): void
    {
        $containerBag = $this->createMock(ContainerBag::class);
        $containerBag->expects($this->exactly(2))->method('has')->willReturnMap([
            ['foo', true],
            ['bar', false],
        ]);
        $containerBag->expects($this->never())->method('get');
        $sut = new Config($containerBag);

        $this->assertTrue($sut->has('foo'));
        $this->assertFalse($sut->has('bar'));
    }

    /**
     * @test
     */
    public function get(): void
    {
        $containerBag = $this->createMock(ContainerBag::class);
        $containerBag->expects($this->once())->method('get')->with('foo')->willReturn('bar');
        $sut = new Config($containerBag);

        $actual = $sut->get('foo');

        $this->assertSame('bar', $actual);
    }

    /**
     * @test
     */
    public function access_parameters_by_period_separator(): void
    {
        $containerBag = $this->createMock(ContainerBag::class);
        $containerBag->expects($this->once())->method('get')->with('foo')->willReturn(['bar' => 'baz']);
        $sut = new Config($containerBag);

        $actual = $sut->get('foo.bar');

        $this->assertSame('baz', $actual);
    }

    /**
     * @test
     */
    public function get_returns_default_value_if_value_is_missed(): void
    {
        /** @var ContainerBag $parameterBag */
        $parameterBag = $this->container()->get('parameter_bag');
        $sut = new Config($parameterBag);

        $actual = $sut->get('foo.bar', 'default');

        $this->assertSame('default', $actual);
    }

    /**
     * @test
     */
    public function get_returns_default_value_if_nested_parameter_is_missed(): void
    {
        $parameterBag = $this->createMock(ContainerBag::class);
        $parameterBag->expects($this->once())->method('get')->with('foo')->willReturn(['bar' => 'baz']);
        $sut = new Config($parameterBag);

        $actual = $sut->get('foo.baz', 'default');

        $this->assertSame('default', $actual);
    }
}
