<?php

declare(strict_types=1);

namespace App\Application\Category\UseCases\CreateCategory;

use App\Application\Category\Contracts\CategoryRepositoryInterface;
use Domain\Category\Entities\Category;
use Domain\Category\ValueObjects\CategoryName;
use Domain\Shared\ValueObjects\Uuid;
use DomainException;

/**
 * CreateCategoryUseCase - Application Layer
 * 
 * Este es el CASO DE USO. Orquesta la lógica de aplicación
 * para crear una categoría. NO conoce detalles de implementación.
 */
final readonly class CreateCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function execute(CreateCategoryDTO $dto): CreateCategoryResponse
    {
        // 1. Validar que el nombre no esté duplicado
        $existingCategory = $this->categoryRepository->findByName($dto->name);
        if ($existingCategory !== null) {
            throw new DomainException('Category with this name already exists');
        }

        // 2. Si tiene padre, validar que exista
        $parentId = null;
        if ($dto->parentId !== null) {
            $parentId = Uuid::fromString($dto->parentId);

            if (!$this->categoryRepository->exists($parentId)) {
                throw new DomainException('Parent category not found');
            }
        }

        // 3. Crear la entidad del dominio
        $category = Category::create(
            name: new CategoryName($dto->name),
            description: $dto->description,
            parentId: $parentId
        );

        // 4. Persistir usando el repositorio
        $this->categoryRepository->save($category);

        // 5. Retornar la respuesta
        return new CreateCategoryResponse(
            id: $category->id()->value(),
            name: $category->name()->value(),
            description: $category->description(),
            parentId: $category->parentId()?->value(),
            isActive: $category->isActive()
        );
    }
}
