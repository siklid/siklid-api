<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Redis;

use App\Foundation\Redis\Set;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;
use Redis;
use RedisException;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress UndefinedClass
 */
class SetTest extends TestCase
{
    use KernelTestCaseTrait;

    private Redis $redis;

    private string $sutKey = 'test:redis:set';

    /**
     * {@inheritDoc}
     *
     * @throws RedisException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->redis = new Redis();
        $host = (string)$this->getConfig('redis.host');
        $this->redis->connect($host);
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function size(): void
    {
        $sut = new Set($this->redis);

        $this->assertSame(0, $sut->size($this->sutKey));
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function add(): void
    {
        $sut = new Set($this->redis);
        $added = $sut->add($this->sutKey, 'member1', 'member2', 'member3');

        $this->assertSame(3, $added);
        $this->assertSame(3, $sut->size($this->sutKey));
        $this->assertSame(0, $sut->add($this->sutKey, 'member1', 'member2', 'member3'));
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function contains(): void
    {
        $sut = new Set($this->redis);
        $sut->add($this->sutKey, 'member1', 'member2', 'member3');

        $this->assertTrue($sut->contains($this->sutKey, 'member1'));
        $this->assertTrue($sut->contains($this->sutKey, 'member2'));
        $this->assertTrue($sut->contains($this->sutKey, 'member3'));
        $this->assertFalse($sut->contains($this->sutKey, 'member4'));
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function members(): void
    {
        $sut = new Set($this->redis);
        $sut->add($this->sutKey, 'member1', 'member2', 'member3');

        $this->assertArrayEquals(['member1', 'member2', 'member3'], $sut->members($this->sutKey));
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function remove(): void
    {
        $sut = new Set($this->redis);
        $sut->add($this->sutKey, 'member1', 'member2', 'member3');

        $removed = $sut->remove($this->sutKey, 'member1', 'member2');

        $this->assertSame(2, $removed);
        $this->assertSame(1, $sut->size($this->sutKey));
        $this->assertArrayEquals(['member3'], $sut->members($this->sutKey));
    }

    /**
     * {@inheritDoc}
     *
     * @throws RedisException
     */
    protected function tearDown(): void
    {
        $this->redis->del($this->sutKey);

        parent::tearDown();
    }
}
