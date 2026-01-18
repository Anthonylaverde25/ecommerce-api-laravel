<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\GetAuthenticatedUser;

/**
 * Respuesta del caso de uso GetAuthenticatedUser
 */
final readonly class GetAuthenticatedUserResponse
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $createdAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'id' => $this->userId,
                'name' => $this->name,
                'email' => $this->email,
                'created_at' => $this->createdAt,
            ],
        ];
    }
}
