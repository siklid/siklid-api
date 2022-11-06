<?php

declare(strict_types=1);

namespace App\Foundation\ValueObject;

use App\Foundation\Exception\ValidationException;
use App\Foundation\Util\Assert;
use Assert\AssertionFailedException;
use JsonSerializable;
use Stringable;

/**
 * Email value object.
 */
final class Email implements Stringable, JsonSerializable
{
    /**
     * @var string
     * @psalm-immutable
     */
    private string $value;

    /**
     * prevents public instantiation.
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * static constructor.
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * @return void
     * @throws AssertionFailedException|ValidationException
     */
    public function validate(): void
    {
        Assert::email($this->value);
    }
}
