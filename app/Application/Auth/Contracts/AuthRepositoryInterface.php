<?php

declare(strict_types=1);

namespace App\Application\Auth\Contracts;

use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;

/**
 * AuthRepositoryInterface - Application Layer
 * 
 * Define las operaciones necesarias para trabajar con usuarios.
 * La implementación estará en Infrastructure Layer.
 */
interface AuthRepositoryInterface
{
    /**
     * Guardar o actualizar un usuario
     */
    public function save(User $user): void;

    /**
     * Buscar usuario por ID
     */
    public function findById(int $id): ?User;

    /**
     * Buscar usuario por email
     */
    public function findByEmail(Email $email): ?User;

    /**
     * Verificar si existe un usuario con el email dado
     */
    public function existsByEmail(Email $email): bool;

    /**
     * Eliminar un usuario
     */
    public function delete(int $id): void;

    /**
     * Crear un token de acceso para el usuario
     */
    public function createToken(User $user, string $deviceName): string;

    /**
     * Revocar el token actual del usuario
     */
    public function revokeCurrentToken(int $userId): void;

    /**
     * Revocar todos los tokens del usuario
     */
    public function revokeAllTokens(int $userId): void;

    /**
     * Obtener todos los tokens del usuario
     */
    public function getUserTokens(int $userId): array;

    /**
     * Revocar un token específico por su ID
     */
    public function revokeTokenById(int $userId, int $tokenId): bool;
}