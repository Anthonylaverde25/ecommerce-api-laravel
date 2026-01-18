<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\LoginUser;

/**
 * Respuesta del caso de uso de login
 */
final readonly class LoginUserResponse
{
    public function __construct(
        public string $token,
        public int $userId,
        public string $name,
        public string $email,
        public array $roles
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
                'roles' => $this->roles,
            ],
        ];
    }
}
