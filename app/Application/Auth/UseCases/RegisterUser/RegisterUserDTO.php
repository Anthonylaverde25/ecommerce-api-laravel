<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\RegisterUser;

/**
 * DTO para el registro de usuarios
 */
final readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $deviceName = 'web-browser'
    ) {
    }
}
