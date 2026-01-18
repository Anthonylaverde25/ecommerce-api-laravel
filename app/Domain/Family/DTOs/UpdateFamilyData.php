<?php
declare(strict_types=1);

namespace App\Domain\Family\DTOs;

/**
 * DTO para actualizar una Family existente
 * Solo contiene campos editables
 */
final readonly class UpdateFamilyData
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public array $tax_ids = []
    ) {
        $this->validate();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            description: $data['description'] ?? null,
            tax_ids: $data['tax_ids'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'tax_ids' => $this->tax_ids,
        ];
    }

    private function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Family name cannot be empty');
        }

        if (strlen(trim($this->name)) < 2) {
            throw new \InvalidArgumentException('Family name must be at least 2 characters');
        }

        if (!is_array($this->tax_ids)) {
            throw new \InvalidArgumentException('tax_ids must be an array');
        }

        foreach ($this->tax_ids as $taxId) {
            if (!is_int($taxId)) {
                throw new \InvalidArgumentException('All tax_ids must be integers');
            }
        }
    }
}
