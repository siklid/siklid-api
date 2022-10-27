<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use Stringable;
use voku\helper\ASCII;

/**
 * Slug value object.
 *
 * @psalm-immutable
 */
final class Slug implements Stringable
{
    public readonly string $slug;

    private readonly string $original;

    /**
     * prevents instantiation from outside the class.
     */
    private function __construct(string $slug, string $original)
    {
        $this->slug = $slug;
        $this->original = $original;
    }

    /**
     * Static constructor from a string.
     */
    public static function fromString(string $str): self
    {
        return new self(ASCII::to_slugify($str), $str);
    }

    public function __toString(): string
    {
        return $this->slug;
    }

    public function equals(Slug $other): bool
    {
        return $this->slug === $other->slug;
    }

    public function original():string
    {
        return $this->original;
    }
}
