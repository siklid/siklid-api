<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use App\Foundation\Util\Assert;
use JsonSerializable;
use Stringable;

/**
 * Email value object.
 *
 * @psalm-immutable
 */
final class Email implements Stringable, JsonSerializable
{
    public readonly string $email;

    /**
     * prevents public instantiation.
     */
    private function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * static constructor.
     */
    public static function fromString(string $email): self
    {
        Assert::email($email);

        return new self($email);
    }

    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): string
    {
        return $this->email;
    }
}
