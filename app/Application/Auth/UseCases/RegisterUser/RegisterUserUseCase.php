<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases\RegisterUser;

use App\Application\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;
use DomainException;

/**
 * RegisterUserUseCase - Application Layer
 * 
 * Orquesta la lógica para registrar un nuevo usuario
 */
final readonly class RegisterUserUseCase
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function execute(RegisterUserDTO $dto): RegisterUserResponse
    {
        // 1. Validar que el email no esté duplicado
        $email = new Email($dto->email);

        if ($this->authRepository->existsByEmail($email)) {
            throw new DomainException('A user with this email already exists');
        }

        // 2. Crear la entidad del dominio
        $user = User::create(
            name: $dto->name,
            email: $email,
            password: Password::fromPlainText($dto->password)
        );

        // 3. Persistir el usuario
        $this->authRepository->save($user);

        // 4. Crear token de autenticación
        $token = $this->authRepository->createToken($user, $dto->deviceName ?? 'web-browser');

        // 5. Retornar la respuesta
        return new RegisterUserResponse(
            token: $token,
            userId: $user->id(),
            name: $user->name(),
            email: $user->emailAsString()
        );
    }
}
