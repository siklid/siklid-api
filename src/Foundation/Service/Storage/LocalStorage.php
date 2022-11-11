<?php

declare(strict_types=1);

namespace App\Foundation\Service\Storage;

/**
 * Local storage service is used to store files on the local file system.
 */
class LocalStorage implements StorageInterface
{
    public function read(string $path): string
    {
        return file_get_contents($path);
    }

    public function write(string $path, string $contents, array $config = []): void
    {
        file_put_contents($path, $contents);
    }
}
