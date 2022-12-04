<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Redis;

use App\Foundation\Redis\Connection;
use App\Foundation\Redis\Contract\ConnectionInterface;
use App\Tests\Concern\KernelTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Redis;
use RedisException;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress MixedArgument - Redis class is defined in the extension
 * @psalm-suppress UndefinedClass - Redis class is defined in the extension
 */
class ConnectionTest extends TestCase
{
    use KernelTestCaseTrait;

    private ConnectionInterface $sut;

    private string $host;

    public function setUp(): void
    {
        $this->host = (string)$this->getConfig('redis.host');

        $this->sut = new Connection(new Redis());
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
