<?php

declare(strict_types=1);

namespace App\Tests\Integration\Siklid\Command;

use App\Foundation\ValueObject\Username;
use App\Siklid\Document\User;
use App\Tests\Concern\CreatesKernel;
use App\Tests\TestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SetupCommandTest extends TestCase
{
    use CreatesKernel;

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

    protected function tearDown(): void
    {
        $this->dropCollection(User::class);
        parent::tearDown();
    }
}
