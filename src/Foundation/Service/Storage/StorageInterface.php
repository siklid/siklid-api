<?php

declare(strict_types=1);

namespace App\Foundation\Service\Storage;

/**
 * All storage services must implement this interface.
 */
interface StorageInterface
{
    /**
     * Reads file contents from the storage.
     */
    public function read(string $path): string;

    /**
     * Writes file contents to the storage.
     */
    public function write(string $path, string $contents, array $config): void;
}
