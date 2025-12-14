<?php

declare(strict_types=1);

namespace App\Application\Category\Contracts;

use Domain\Category\Entities\Category;
use Domain\Shared\ValueObjects\Uuid;

/**
 * CategoryRepositoryInterface - Application Layer
 * 
 * Esta es la INTERFAZ (contrato) que define qué operaciones
 * necesitamos para trabajar con categorías.
 * 
 * La IMPLEMENTACIÓN estará en Infrastructure Layer.
 */
interface CategoryRepositoryInterface
{
    /**
     * Guardar o actualizar una categoría
     */
    public function save(Category $category): void;

    /**
     * Buscar categoría por ID
     */
    public function findById(Uuid $id): ?Category;

    /**
     * Obtener todas las categorías
     */
    public function findAll(): array;

    /**
     * Obtener solo categorías activas
     */
    public function findActive(): array;

    /**
     * Obtener categorías de primer nivel (sin padre)
     */
    public function findRootCategories(): array;

    /**
     * Obtener subcategorías de una categoría padre
     */
    public function findByParentId(Uuid $parentId): array;

    /**
     * Verificar si existe una categoría
     */
    public function exists(Uuid $id): bool;

    /**
     * Eliminar una categoría
     */
    public function delete(Uuid $id): void;

    /**
     * Buscar categoría por nombre
     */
    public function findByName(string $name): ?Category;
}
