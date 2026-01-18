<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObjects;

use InvalidArgumentException;

/**
 * Password Value Object
 * 
 * Encapsula y valida contraseñas
 */
final class Password
{
    private const MIN_LENGTH = 8;

    private function __construct(
        private readonly string $value,
        private readonly bool $isHashed = false
    ) {
    }

    /**
     * Crear desde texto plano (validará la fortaleza)
     */
    public static function fromPlainText(string $plainText): self
    {
        self::validate($plainText);
        return new self($plainText, false);
    }

    /**
     * Crear desde hash (ya hasheada en DB)
     */
    public static function fromHash(string $hash): self
    {
        if (empty($hash)) {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }

        return new self($hash, true);
    }

    private static function validate(string $password): void
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }

        if (strlen($password) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                "Password must be at least " . self::MIN_LENGTH . " characters long"
            );
        }
    }

    /**
     * Hashear la contraseña usando bcrypt
     */
    public function hash(): string
    {
        if ($this->isHashed) {
            return $this->value;
        }

        return password_hash($this->value, PASSWORD_BCRYPT);
    }

    /**
     * Verificar si una contraseña en texto plano coincide
     */
    public function verify(string $plainText): bool
    {
        if (!$this->isHashed) {
            throw new \LogicException('Cannot verify against unhashed password');
        }

        return password_verify($plainText, $this->value);
    }

    /**
     * Obtener el valor (solo para uso interno)
     */
    public function value(): string
    {
        return $this->value;
    }

    public function isHashed(): bool
    {
        return $this->isHashed;
    }
}
