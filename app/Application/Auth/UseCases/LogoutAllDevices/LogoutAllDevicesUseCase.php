<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\LogoutAllDevices;

use App\Application\Auth\Contracts\AuthRepositoryInterface;

/**
 * LogoutAllDevicesUseCase - Application Layer
 * 
 * Cierra todas las sesiones del usuario
 */
final readonly class LogoutAllDevicesUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(int $userId): void
    {
        $this->authRepository->revokeAllTokens($userId);
    }
}
