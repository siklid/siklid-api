<?php

declare(strict_types=1);

namespace App\Siklid\Command;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'siklid:postman',
    description: 'Generates a Postman collection from the API documentation'
)]
class PostmanCommand extends Console
{
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
