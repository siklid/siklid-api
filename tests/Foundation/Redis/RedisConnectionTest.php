<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Redis;

use App\Foundation\Redis\Connection;
use App\Tests\Concern\KernelTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Redis;
use RedisException;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress MixedArgument - Redis class is defined in the extension
 * @psalm-suppress UndefinedClass - Redis class is defined in the extension
 */
class RedisConnectionTest extends TestCase
{
    use KernelTestCaseTrait;

    private Connection $sut;

    private string $host;

    public function setUp(): void
    {
        $this->sut = new Connection(new Redis());

        $this->host = (string)$this->getConfig('redis.host');
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function connect(): void
    {
        $this->expectNotToPerformAssertions();

        $this->sut->connect($this->host);
    }

    /**
     * @test
     *
     * @psalm-suppress MixedArgument - RedisException class is defined in the extension
     * @psalm-suppress InvalidArgument - RedisException is throwable
     * @psalm-suppress UndefinedClass - RedisException class is defined in the extension
     */
    public function connect_with_invalid_host(): void
    {
        $this->expectException(RedisException::class);

        $this->sut->connect('invalid-host');
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function ping(): void
    {
        $this->sut->connect($this->host);

        $actual = $this->sut->ping();

        $this->assertSame('PONG', $actual);
    }

    /**
     * @test
     *
     * @throws RedisException
     */
    public function close(): void
    {
        $this->assertIsBool($this->sut->close());
    }
}
