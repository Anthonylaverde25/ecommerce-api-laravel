<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\RegisterUser;

/**
 * Respuesta del caso de uso de registro
 */
final readonly class RegisterUserResponse
{
    public function __construct(
        public string $token,
        public int $userId,
        public string $name,
        public string $email
    ) {
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'user' => [
                'id' => $this->userId,
                'name' => $this->name,
                'email' => $this->email,
            ],
        ];
    }
}
