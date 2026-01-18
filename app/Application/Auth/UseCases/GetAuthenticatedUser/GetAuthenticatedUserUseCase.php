<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\GetAuthenticatedUser;

use App\Application\Auth\Contracts\AuthRepositoryInterface;
use DomainException;

/**
 * GetAuthenticatedUserUseCase - Application Layer
 * 
 * Obtiene los datos del usuario autenticado
 */
final readonly class GetAuthenticatedUserUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(int $userId): GetAuthenticatedUserResponse
    {
        $user = $this->authRepository->findById($userId);

        if ($user === null) {
            throw new DomainException('User not found');
        }

        return new GetAuthenticatedUserResponse(
            userId: $user->id(),
            name: $user->name(),
            email: $user->emailAsString(),
            createdAt: $user->createdAt()->format('Y-m-d H:i:s')
        );
    }
}
