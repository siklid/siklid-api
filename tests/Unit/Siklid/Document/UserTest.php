<?php

declare(strict_types=1);

namespace App\Tests\Unit\Siklid\Document;

use App\Siklid\Document\User;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class UserTest extends TestCase
{
    /**
     * @test
     */
    public function erase_credentials_only_wont_erase_if_it_should_not(): void
    {
        $sut = new User();
        $sut->setPassword('password');
        $sut->setShouldEraseCredentials(false);

        $sut->eraseCredentials();

        $this->assertEquals('password', $sut->getPassword());
    }

    /**
     * @test
     */
    public function erase_credentials_will_erase_if_it_should(): void
    {
        $sut = new User();
        $sut->setPassword('password');
        $sut->setShouldEraseCredentials(true);

        $sut->eraseCredentials();

        $this->assertEquals('', $sut->getPassword());
    }
}
