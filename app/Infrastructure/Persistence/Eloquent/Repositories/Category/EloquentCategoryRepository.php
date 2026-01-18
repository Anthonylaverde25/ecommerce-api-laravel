<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories\Category;

use App\Application\Category\Contracts\CategoryRepositoryInterface;
use Domain\Category\Entities\Category;
use Domain\Shared\ValueObjects\Uuid;
use App\Models\Category as CategoryModel;

/**
 * EloquentCategoryRepository - Infrastructure Layer
 * 
 * Esta es la IMPLEMENTACIÓN del repositorio usando Eloquent.
 * Implementa la interfaz definida en Application Layer.
 * 
 * Responsabilidades:
 * - Convertir entre Entities (Domain) y Models (Eloquent)
 * - Realizar las operaciones de persistencia
 */
final class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function save(Category $category): void
    {
        CategoryModel::updateOrCreate(
            ['id' => $category->id()->value()],
            [
                'name' => $category->name()->value(),
                'description' => $category->description(),
                'parent_id' => $category->parentId()?->value(),
                'is_active' => $category->isActive(),
            ]
        );
    }

    public function findById(Uuid $id): ?Category
    {
        $model = CategoryModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return CategoryModel::all()
            ->map(fn(CategoryModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findActive(): array
    {
        return CategoryModel::active()
            ->get()
            ->map(fn(CategoryModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findRootCategories(): array
    {
        return CategoryModel::root()
            ->active()
            ->get()
            ->map(fn(CategoryModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findByParentId(Uuid $parentId): array
    {
        return CategoryModel::where('parent_id', $parentId->value())
            ->active()
            ->get()
            ->map(fn(CategoryModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function exists(Uuid $id): bool
    {
        return CategoryModel::where('id', $id->value())->exists();
    }

    public function delete(Uuid $id): void
    {
        CategoryModel::destroy($id->value());
    }

    public function findByName(string $name): ?Category
    {
        $model = CategoryModel::where('name', $name)->first();

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Convierte un Category Model (Eloquent - App\Models) 
     * a Category Entity (Domain - Domain\Category\Entities)
     */
    private function toDomain(CategoryModel $model): Category
    {
        return Category::fromPrimitives(
            id: $model->id,
            name: $model->name,
            description: $model->description ?? '',
            parentId: $model->parent_id,
            isActive: $model->is_active
        );
    }
}
