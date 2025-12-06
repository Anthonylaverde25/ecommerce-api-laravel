<?php

declare(strict_types=1);

namespace Domain\Category\ValueObjects;

use InvalidArgumentException;

/**
 * CategoryName Value Object - Domain Layer
 * 
 * Encapsula el nombre de la categoría con sus reglas de validación
 */
final readonly class CategoryName
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 100;

    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        $trimmed = trim($this->value);

        if (empty($trimmed)) {
            throw new InvalidArgumentException('Category name cannot be empty');
        }

        $length = mb_strlen($trimmed);

        if ($length < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Category name must be at least %d characters', self::MIN_LENGTH)
            );
        }

        if ($length > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Category name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(CategoryName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
