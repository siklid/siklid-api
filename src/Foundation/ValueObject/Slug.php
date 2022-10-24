<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use App\Foundation\Util\Assert;
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

    /**
     * prevents instantiation from outside the class.
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Static constructor from a string.
     */
    public static function fromString(string $slug): self
    {
        $regex = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

        Assert::regex($slug, $regex, 'Slug must be a string of lowercase letters, numbers and hyphens.');

        return new self($slug);
    }

    /**
     * Static constructor from a string in any case.
     */
    public static function slugify(string $str): self
    {
        return new self(ASCII::to_slugify($str));
    }

    public function __toString(): string
    {
        return $this->slug;
    }

    public function equals(Slug $other): bool
    {
        return $this->slug === $other->slug;
    }
}
