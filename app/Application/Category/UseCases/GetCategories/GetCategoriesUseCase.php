<?php

declare(strict_types=1);

namespace App\Application\Category\UseCases\GetCategories;

use App\Application\Category\Contracts\CategoryRepositoryInterface;

/**
 * GetCategoriesUseCase - Application Layer
 * 
 * Caso de uso para obtener todas las categorías activas
 */
final readonly class GetCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function execute(): array
    {
        return $this->categoryRepository->findActive();
    }

    public function executeAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function executeRootCategories(): array
    {
        return $this->categoryRepository->findRootCategories();
    }
}
