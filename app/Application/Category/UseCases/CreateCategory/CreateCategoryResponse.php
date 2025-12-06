<?php

declare(strict_types=1);

namespace Application\Category\UseCases\CreateCategory;

/**
 * CreateCategoryResponse - Application Layer
 * 
 * Respuesta del caso de uso CreateCategory
 */
final readonly class CreateCategoryResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public ?string $parentId,
        public bool $isActive
    ) {
    }
}
