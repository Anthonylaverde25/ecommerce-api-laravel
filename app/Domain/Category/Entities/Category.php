<?php

declare(strict_types=1);

namespace Domain\Category\Entities;

use Domain\Category\ValueObjects\CategoryName;

/**
 * Category Entity - Domain Layer
 * 
 * Esta es la entidad del dominio. Contiene la lógica de negocio
 * y las reglas de validación. NO depende de ningún framework.
 * 
 * NOTA: Usamos IDs auto-incrementales en lugar de UUIDs para
 * simplificar y seguir la convención de Laravel.
 */
final class Category
{
    private function __construct(
        private readonly int $id,
        private CategoryName $name,
        private ?string $description,
        private ?int $parentId,
        private bool $isActive
    ) {
    }

    /**
     * Named constructor para crear una nueva categoría
     * NOTA: El ID será asignado por la base de datos
     */
    public static function create(
        CategoryName $name,
        ?string $description = null,
        ?int $parentId = null
    ): self {
        return new self(
            id: 0, // El ID será asignado por la DB
            name: $name,
            description: $description,
            parentId: $parentId,
            isActive: true
        );
    }

    /**
     * Factory method para reconstruir desde primitivos
     * (usado por el repositorio al leer desde DB)
     */
    public static function fromPrimitives(
        int $id,
        string $name,
        ?string $description,
        ?int $parentId,
        bool $isActive
    ): self {
        return new self(
            id: $id,
            name: new CategoryName($name),
            description: $description,
            parentId: $parentId,
            isActive: $isActive
        );
    }

    /**
     * Métodos de negocio
     */
    public function updateName(CategoryName $newName): void
    {
        $this->name = $newName;
    }

    public function updateDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function setParent(?int $parentId): void
    {
        // Aquí podrías agregar validación de negocio
        // Por ejemplo, verificar que no se cree una relación circular
        $this->parentId = $parentId;
    }

    /**
     * Getters
     */
    public function id(): int
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function parentId(): ?int
    {
        return $this->parentId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function hasParent(): bool
    {
        return $this->parentId !== null;
    }
}
