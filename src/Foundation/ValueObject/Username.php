<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use JsonSerializable;
use Stringable;

/**
 * Username value object.
 *
 * @psalm-immutable
 */
final class Username implements Stringable, JsonSerializable
{
    private readonly string $username;

    private readonly string $original;

    private function __construct(string $username, string $original)
    {
        $this->username = $username;
        $this->original = $original;
    }

    public static function fromString(string $str): self
    {
        // Replace spaces with dots
        $username = str_replace(' ', '.', $str);

        // Remove all characters except letters, numbers and dots
        $username = preg_replace('/[^A-Za-z0-9.]/', '', $username);

        // Remove dots from the beginning and the end
        $username = trim($username, '.');

        // Remove multiple dots
        $username = preg_replace('/\.+/', '.', $username);

        return new self(trim($username), $str);
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function original(): string
    {
        return $this->original;
    }

    public function jsonSerialize(): string
    {
        return $this->username;
    }
}
