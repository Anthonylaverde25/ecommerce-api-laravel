<?php

declare(strict_types=1);

namespace Application\Category\UseCases\CreateCategory;

/**
 * CreateCategoryDTO - Application Layer
 * 
 * Data Transfer Object para transferir datos de entrada
 * al caso de uso CreateCategory
 */
final readonly class CreateCategoryDTO
{
    public function __construct(
        public string $name,
        public string $description = '',
        public ?string $parentId = null
    ) {
    }

    /**
     * Factory method para crear desde array
     * (útil para crear desde request HTTP)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? '',
            parentId: $data['parent_id'] ?? null
        );
    }
}
