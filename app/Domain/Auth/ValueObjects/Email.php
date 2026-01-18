<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObjects;

use InvalidArgumentException;

/**
 * Email Value Object
 * 
 * Encapsula y valida direcciones de email
 */
final readonly class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    private function validate(string $value): void
    {
        $email = trim($value);

        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$email}");
        }

        // Validar longitud máxima
        if (strlen($email) > 255) {
            throw new InvalidArgumentException('Email cannot exceed 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
