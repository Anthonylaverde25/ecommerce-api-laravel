<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entities;

use App\Domain\Auth\DTOs\CreateUserData;
use App\Domain\Auth\DTOs\UpdateUserData;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;
use DateTimeImmutable;

/**
 * User Entity - Domain Layer
 * 
 * Esta es la entidad del dominio. Contiene la lógica de negocio
 * y las reglas de validación. NO depende de ningún framework.
 */
final class User
{
    private function __construct(
        private int $id,
        private string $name,
        private Email $email,
        private Password $password,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private array $role_ids = [],
        private array $roles = [],
        private array $departments = [],
        private array $department_ids = [],

    ) {
    }

    /**
     * Named constructor para crear un nuevo usuario
     * NOTA: El ID será asignado por la base de datos
     */
    public static function create(
        CreateUserData $data
    ): self {
        return new self(
            id: 0,
            name: $data->name,
            email: new Email($data->email),
            password: Password::fromHash($data->password),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            role_ids: $data->role_ids,
            department_ids: $data->department_ids,


        );
    }

    /**
     * Factory method para reconstruir desde primitivos
     * (usado por el repositorio al leer desde DB)
     */
    public static function fromPrimitives(
        int $id,
        string $name,
        string $email,
        string $hashedPassword,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        array $roles = [],
        array $departments = []
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: new Email($email),
            password: Password::fromHash($hashedPassword),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            roles: $roles,
            departments: $departments
        );
    }

    /**
     * Métodos de negocio
     */
    public function updateName(string $newName): void
    {
        if (empty(trim($newName))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->name = $newName;
        $this->touch();
    }

    public function updateEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
        $this->touch();
    }

    public function changePassword(Password $newPassword): void
    {
        $this->password = $newPassword;
        $this->touch();
    }

    public function verifyPassword(string $plainTextPassword): bool
    {
        return $this->password->verify($plainTextPassword);
    }

    /**
     * Actualiza el timestamp de modificación
     */
    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Setters especiales (solo para uso del repositorio)
     */
    public function setId(int $id): void
    {
        if ($this->id !== 0) {
            throw new \LogicException('Cannot change ID of existing user');
        }

        $this->id = $id;
    }

    /**
     * Getters
     */
    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Helper methods
     */
    public function emailAsString(): string
    {
        return $this->email->value();
    }

    public function hashedPassword(): string
    {
        return $this->password->hash();
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function departments(): array
    {
        return $this->departments;
    }

    public function roleIds(): array
    {
        return $this->role_ids;
    }

    public function departmentIds(): array
    {
        return $this->department_ids;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->touch();
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
        $this->touch();
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
        $this->touch();
    }

    public function setRoleIds(array $role_ids): void
    {
        $this->role_ids = $role_ids;
        $this->touch();
    }

    public function setDepartmentIds(array $department_ids): void
    {
        $this->department_ids = $department_ids;
        $this->touch();


    }

    public function update(UpdateUserData $data): void
    {
        if ($data->name !== null) {
            $this->setName($data->name);
        }
        if ($data->email !== null) {
            $this->setEmail(new Email($data->email));
        }
        if ($data->password !== null) {
            $this->setPassword(Password::fromHash($data->password));
        }
        if ($data->role_ids !== null) {
            $this->setRoleIds($data->role_ids);
        }

        if ($data->department_ids !== null) {
            $this->setDepartmentIds($data->department_ids);
        }
    }
}
