<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\LoginUser;

use App\Application\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\ValueObjects\Email;
use DomainException;

/**
 * LoginUserUseCase - Application Layer
 * 
 * Orquesta la lógica para autenticar un usuario
 */
final readonly class LoginUserUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(LoginUserDTO $dto): LoginUserResponse
    {
        // 1. Buscar usuario por email
        $email = new Email($dto->email);
        $user = $this->authRepository->findByEmail($email);

        if ($user === null) {
            throw new DomainException('Invalid credentials');
        }

        // 2. Verificar contraseña
        if (!$user->verifyPassword($dto->password)) {
            throw new DomainException('Invalid credentials');
        }

        // 3. Crear token de autenticación
        $token = $this->authRepository->createToken($user, $dto->deviceName ?? 'web-browser');

        // 4. Retornar la respuesta
        return new LoginUserResponse(
            token: $token,
            userId: $user->id(),
            name: $user->name(),
            email: $user->emailAsString(),
            roles: $user->roles(),
        );
    }
}
