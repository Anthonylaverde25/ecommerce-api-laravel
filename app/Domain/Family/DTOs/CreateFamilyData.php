<?php
declare(strict_types=1);

namespace App\Domain\Family\DTOs;

/**
 * DTO (Data Transfer Object) para crear una nueva Family
 * 
 * Este objeto transporta los datos necesarios para crear una entidad Family.
 * - Es inmutable (readonly)
 * - Valida los datos en el constructor
 * - No tiene lógica de negocio, solo transporta datos
 */
final readonly class CreateFamilyData
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description = null,
        public array $tax_ids = []
    ) {
        // Validar datos al momento de construir el DTO
        $this->validate();
    }

    /**
     * Factory method para crear el DTO desde un array
     * Útil cuando recibes datos desde un HTTP Request o API
     * 
     * @param array $data
     * @return self
     * @throws \InvalidArgumentException si faltan campos requeridos
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            code: $data['code'] ?? throw new \InvalidArgumentException('code is required'),
            description: $data['description'] ?? null,
            tax_ids: $data['tax_ids'] ?? []
        );
    }

    /**
     * Convertir el DTO a array
     * Útil para logging, serialización, etc.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'tax_ids' => $this->tax_ids,
        ];
    }

    /**
     * Validaciones de negocio
     * Se ejecutan automáticamente al crear el DTO
     * 
     * @throws \InvalidArgumentException
     */
    private function validate(): void
    {
        // Validar nombre no vacío
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Family name cannot be empty');
        }

        // Validar que el nombre tenga al menos 2 caracteres
        if (strlen(trim($this->name)) < 2) {
            throw new \InvalidArgumentException('Family name must be at least 2 characters');
        }

        // Validar código no vacío
        if (empty(trim($this->code))) {
            throw new \InvalidArgumentException('Family code cannot be empty');
        }

        // Validar que el código tenga al menos 2 caracteres
        if (strlen(trim($this->code)) < 2) {
            throw new \InvalidArgumentException('Family code must be at least 2 characters');
        }

        // Validar que tax_ids sea un array
        if (!is_array($this->tax_ids)) {
            throw new \InvalidArgumentException('tax_ids must be an array');
        }

        // Validar que todos los elementos de tax_ids sean enteros
        foreach ($this->tax_ids as $taxId) {
            if (!is_int($taxId)) {
                throw new \InvalidArgumentException('All tax_ids must be integers');
            }
        }
    }
}
