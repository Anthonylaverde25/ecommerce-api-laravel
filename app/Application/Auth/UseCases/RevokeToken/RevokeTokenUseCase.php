<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\RevokeToken;

use App\Application\Auth\Contracts\AuthRepositoryInterface;
use DomainException;

/**
 * RevokeTokenUseCase - Application Layer
 * 
 * Revoca un token específico del usuario
 */
final readonly class RevokeTokenUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(int $userId, int $tokenId): void
    {
        $revoked = $this->authRepository->revokeTokenById($userId, $tokenId);

        if (!$revoked) {
            throw new DomainException('Token not found or does not belong to user');
        }
    }
}
