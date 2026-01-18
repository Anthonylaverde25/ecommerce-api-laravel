<?php
declare(strict_types=1);

namespace App\Domain\Role\Entities;

use App\Domain\Role\DTOs\CreateRoleData;

/**
 * Role Entity - Domain Layer
 * 
 * Represents a role in the system with its permissions.
 * Follows DDD principles with factory methods and encapsulated business logic.
 */
final class Role
{
    private function __construct(
        private int $id,
        private string $name,
        private ?string $description,
        private bool $active,
        private array $permissions = []
    ) {
    }

    /**
     * Factory method to create a new Role entity (without ID)
     * Uses CreateRoleData DTO which is already validated
     */
    public static function create(CreateRoleData $data): self
    {
        return new self(
            id: 0, // ID will be assigned when persisted to DB
            name: $data->name,
            description: $data->description ?? null,
            active: true, // Roles are created active by default
            permissions: $data->permissions ?? []
        );
    }

    /**
     * Named constructor to reconstruct from database
     * Uses primitives because data is already persisted and validated
     */
    public static function fromPrimitives(
        int $id,
        string $name,
        ?string $description = null,
        bool $active = true,
        array $permissions = []
    ): self {
        return new self($id, $name, $description, $active, $permissions);
    }

    // ========== GETTERS ==========

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    // ========== SETTERS ==========

    public function setId(int $id): void
    {
        if ($this->id !== 0) {
            throw new \LogicException('Cannot change ID of existing role');
        }
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Role name cannot be empty');
        }
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }
}