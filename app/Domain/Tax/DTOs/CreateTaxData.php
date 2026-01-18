<?php
declare(strict_types=1);
namespace App\Domain\Tax\DTOs;
/**
 * DTO (Data Transfer Object) para crear un nuevo Tax
 * 
 * Este objeto transporta los datos necesarios para crear una entidad Tax.
 * - Es inmutable (readonly)
 * - Valida los datos en el constructor
 * - No tiene lógica de negocio, solo transporta datos
 */
final readonly class CreateTaxData
{
    public function __construct(
        public string $tax_code,
        public string $name,
        public int $tax_type_id,
        public float $percentage,
        public ?string $description = null
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
            tax_code: $data['tax_code'] ?? throw new \InvalidArgumentException('tax_code is required'),
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            tax_type_id: $data['tax_type_id'] ?? throw new \InvalidArgumentException('tax_type_id is required'),
            percentage: (float) ($data['percentage'] ?? throw new \InvalidArgumentException('percentage is required')),
            description: $data['description'] ?? null
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
            'tax_code' => $this->tax_code,
            'name' => $this->name,
            'tax_type_id' => $this->tax_type_id,
            'percentage' => $this->percentage,
            'description' => $this->description,
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
            throw new \InvalidArgumentException('Tax name cannot be empty');
        }

        // Validar que el nombre tenga al menos 2 caracteres
        if (strlen(trim($this->name)) < 2) {
            throw new \InvalidArgumentException('Tax name must be at least 2 characters');
        }

        // Validar porcentaje entre 0 y 100
        if ($this->percentage < 0 || $this->percentage > 100) {
            throw new \InvalidArgumentException('Tax percentage must be between 0 and 100');
        }

        // Validar que tax_type_id sea positivo
        if ($this->tax_type_id <= 0) {
            throw new \InvalidArgumentException('Tax type ID must be a positive integer');
        }
    }
}
