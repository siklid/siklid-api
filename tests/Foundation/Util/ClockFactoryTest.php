<?php

declare(strict_types=1);

namespace App\Tests\Foundation\Util;

use App\Foundation\Util\ClockFactory;
use App\Tests\TestCase;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\Clock\SystemClock;

class ClockFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function create_system_clock(): void
    {
        $clock = ClockFactory::create();

        $this->assertInstanceOf(SystemClock::class, $clock);
    }

    /**
     * @test
     */
    public function create_frozen_clock(): void
    {
        $clock = ClockFactory::create(true);

        $this->assertInstanceOf(FrozenClock::class, $clock);
    }

    /**
     * @test
     */
    public function system_clock_timezone_is_utc(): void
    {
        $clock = ClockFactory::create();

        $this->assertEquals('UTC', $clock->now()->getTimezone()->getName());
    }
}
