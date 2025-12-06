<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * Uuid Value Object - Shared Domain
 */
final readonly class Uuid
{
    private function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(): void
    {
        if (!RamseyUuid::isValid($this->value)) {
            throw new InvalidArgumentException(
                sprintf('Invalid UUID: %s', $this->value)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
