<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Action;

use App\Foundation\Action\Config;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigTest extends TestCase
{
    use KernelTestCaseTrait;

    /**
     * @test
     */
    public function all(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('all')->willReturn(['foo' => 'bar']);
        $sut = new Config($parameterBag);

        $actual = $sut->all();

        $this->assertSame(['foo' => 'bar'], $actual);
    }

    /**
     * @test
     */
    public function has(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->exactly(2))->method('has')->willReturnMap([
            ['foo', true],
            ['bar', false],
        ]);
        $parameterBag->expects($this->never())->method('get');
        $sut = new Config($parameterBag);

        $this->assertTrue($sut->has('foo'));
        $this->assertFalse($sut->has('bar'));
    }

    /**
     * @test
     */
    public function get(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('get')->with('foo')->willReturn('bar');
        $sut = new Config($parameterBag);

        $actual = $sut->get('foo');

        $this->assertSame('bar', $actual);
    }

    /**
     * @test
     */
    public function access_parameters_by_period_separator(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('get')->with('foo')->willReturn(['bar' => 'baz']);
        $sut = new Config($parameterBag);

        $actual = $sut->get('foo.bar');

        $this->assertSame('baz', $actual);
    }

    /**
     * @test
     */
    public function get_returns_default_value_if_value_is_missed(): void
    {
        /** @var ParameterBagInterface $parameterBag */
        $parameterBag = $this->container()->get('parameter_bag');
        $sut = new Config($parameterBag);

        $this->assertSame('default', $sut->get('foo.bar', 'default'));
        $this->assertSame('default', $sut->get('@foo.bar', 'default'));
    }

    /**
     * @test
     */
    public function get_returns_default_value_if_nested_parameter_is_missed(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('get')->with('foo')->willReturn(['bar' => 'baz']);
        $sut = new Config($parameterBag);

        $actual = $sut->get('foo.baz', 'default');

        $this->assertSame('default', $actual);
    }

    /**
     * @test
     */
    public function access_keys_that_have_periods_in_names(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $parameterBag->expects($this->once())->method('get')->with('foo.bar')->willReturn('baz');
        $sut = new Config($parameterBag);

        $actual = $sut->get('@foo.bar');

        $this->assertSame('baz', $actual);
    }
}
