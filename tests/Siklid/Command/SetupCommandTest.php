<?php

declare(strict_types=1);

namespace App\Tests\Siklid\Command;

use App\Foundation\ValueObject\Username;
use App\Siklid\Document\User;
use App\Tests\Concern\KernelTestCaseTrait;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SetupCommandTest extends TestCase
{
    use KernelTestCaseTrait;

    /**
     * @test
     */
    public function execute(): void
    {
        $console = $this->cmdTester($this->consoleApplication(), 'siklid:setup');

        $console->execute([]);

        $console->assertCommandIsSuccessful();
        $this->assertExists(User::class, ['username' => Username::fromString('admin')]);
    }
}
