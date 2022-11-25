<?php

declare(strict_types=1);

namespace App\Foundation\Util;

use Lcobucci\Clock\FrozenClock;
use Lcobucci\Clock\SystemClock;
use StellaMaris\Clock\ClockInterface;

/**
 * This class is used to unify the way we get the clock instance across the application.
 */
final class ClockFactory
{
    public static function create(bool $frozen = false): ClockInterface
    {
        return $frozen ? FrozenClock::fromUTC() : SystemClock::fromUTC();
    }
}
