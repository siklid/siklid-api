<?php

declare(strict_types=1);

namespace App\Tests\Integration\Siklid\Command;

use App\Foundation\ValueObject\Username;
use App\Siklid\Document\User;
use App\Tests\IntegrationTestCase;

/**
 * @psalm-suppress MissingConstructor
 */
class SetupCommandTest extends IntegrationTestCase
{
    /**
     * @test
     */
    public function execute(): void
    {
        $console = $this->cmdTester($this->consoleApplication(), 'siklid:setup');

        $console->execute([]);

        $console->assertCommandIsSuccessful();
        $this->assertExists(User::class, ['username' => Username::fromString('admin')]);

        $this->deleteDocument(User::class, ['username' => Username::fromString('admin')]);
    }
}
