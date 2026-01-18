<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories\Auth;

use App\Application\Auth\Contracts\AuthRepositoryInterface;
use App\Models\User as EloquentUser;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;
use Illuminate\Support\Facades\Request;

/**
 * EloquentAuthRepository - Infrastructure Layer
 * 
 * Implementación del repositorio usando Eloquent ORM
 */
class EloquentAuthRepository implements AuthRepositoryInterface
{
    /**
     * Guardar o actualizar un usuario
     */
    public function save(User $user): void
    {
        $eloquentUser = EloquentUser::updateOrCreate(
            ['id' => $user->id() === 0 ? null : $user->id()],
            [
                'name' => $user->name(),
                'email' => $user->emailAsString(),
                'password' => $user->hashedPassword(),
            ]
        );

        // Si es un nuevo usuario, actualizar el ID en la entidad
        if ($user->id() === 0) {
            $user->setId($eloquentUser->id);
        }
    }

    /**
     * Buscar usuario por ID
     */
    public function findById(int $id): ?User
    {
        $eloquentUser = EloquentUser::with('roles')->find($id);

        if (!$eloquentUser) {
            return null;
        }

        return $this->mapToEntity($eloquentUser);
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::with('roles')->where('email', $email->value())->first();

        if (!$eloquentUser) {
            return null;
        }

        return $this->mapToEntity($eloquentUser);
    }

    /**
     * Verificar si existe un usuario con el email dado
     */
    public function existsByEmail(Email $email): bool
    {
        return EloquentUser::where('email', $email->value())->exists();
    }

    /**
     * Eliminar un usuario
     */
    public function delete(int $id): void
    {
        EloquentUser::destroy($id);
    }

    /**
     * Crear un token de acceso para el usuario
     */
    public function createToken(User $user, string $deviceName): string
    {
        // $eloquentUser = EloquentUser::find($user->id());
        $eloquentUser = EloquentUser::with('roles')->find($user->id());


        if (!$eloquentUser) {
            throw new \RuntimeException('User not found');
        }

        return $eloquentUser->createToken($deviceName)->plainTextToken;
    }

    /**
     * Revocar el token actual del usuario
     */
    public function revokeCurrentToken(int $userId): void
    {
        $eloquentUser = EloquentUser::find($userId);

        if ($eloquentUser && method_exists(app('request'), 'user')) {
            $token = app('request')->user()->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }
    }

    /**
     * Revocar todos los tokens del usuario
     */
    public function revokeAllTokens(int $userId): void
    {
        $eloquentUser = EloquentUser::find($userId);

        if ($eloquentUser) {
            $eloquentUser->tokens()->delete();
        }
    }

    /**
     * Obtener todos los tokens del usuario
     */
    public function getUserTokens(int $userId): array
    {
        $eloquentUser = EloquentUser::find($userId);

        if (!$eloquentUser) {
            return [];
        }

        return $eloquentUser->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at?->format('Y-m-d H:i:s'),
                'created_at' => $token->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    /**
     * Revocar un token específico por su ID
     */
    public function revokeTokenById(int $userId, int $tokenId): bool
    {
        $eloquentUser = EloquentUser::find($userId);

        if (!$eloquentUser) {
            return false;
        }

        $token = $eloquentUser->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return false;
        }

        $token->delete();
        return true;
    }

    /**
     * Mapear modelo Eloquent a entidad de dominio
     */
    private function mapToEntity(EloquentUser $eloquentUser): User
    {
        return User::fromPrimitives(
            id: $eloquentUser->id,
            name: $eloquentUser->name,
            email: $eloquentUser->email,
            hashedPassword: $eloquentUser->password,
            createdAt: new \DateTimeImmutable($eloquentUser->created_at->format('Y-m-d H:i:s')),
            updatedAt: new \DateTimeImmutable($eloquentUser->updated_at->format('Y-m-d H:i:s')),
            roles: $eloquentUser->roles->pluck('name')->toArray()
        );
    }
}
