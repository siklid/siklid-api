<?php

namespace App\Tests\Integration\Siklid\Command;

use App\Siklid\Document\User;
use App\Tests\IntegrationTestCase;

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
        $this->assertExists(User::class, ['username' => 'admin']);
    }
}
