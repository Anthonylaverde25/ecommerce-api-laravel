<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\LogoutUser;

use App\Application\Auth\Contracts\AuthRepositoryInterface;

/**
 * LogoutUserUseCase - Application Layer
 * 
 * Cierra la sesión del dispositivo actual
 */
final readonly class LogoutUserUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(int $userId): void
    {
        $this->authRepository->revokeCurrentToken($userId);
    }
}
