<?php
declare(strict_types=1);
namespace App\Domain\Tax\Entities;

use App\Domain\Tax\DTOs\CreateTaxData;
use App\Domain\Tax\DTOs\UpdateTaxData;

/**
 * Entidad Tax del dominio
 * 
 * Representa un impuesto en el sistema.
 * Utiliza DTOs para creación y primitivos para reconstrucción desde BD.
 */
final class Tax
{
    private function __construct(
        private int $id,
        private string $tax_code,
        private string $name,
        private int $tax_type_id,
        private float $percentage,
        private bool $active,
        private ?string $description = null,
        private ?TaxType $taxType = null
    ) {
    }

    /**
     * Factory method para crear nueva entidad (sin ID)
     * Usa el DTO CreateTaxData que ya viene validado
     * 
     * @param CreateTaxData $data DTO con los datos validados
     * @return self
     */
    public static function create(CreateTaxData $data): self
    {
        return new self(
            id: 0, // El ID se asignará cuando se persista en la BD
            tax_code: $data->tax_code,
            name: $data->name,
            tax_type_id: $data->tax_type_id,
            percentage: $data->percentage,
            active: true, // Por defecto los impuestos se crean activos
            description: $data->description,
        );
    }

    /**
     * Named constructor para reconstruir desde base de datos
     * Usa primitivos porque los datos ya están persistidos y validados
     * 
     * @param int $id
     * @param string $tax_code
     * @param string $name
     * @param int $tax_type_id
     * @param float $percentage
     * @return self
     */
    public static function fromPrimitives(
        int $id,
        string $tax_code,
        string $name,
        int $tax_type_id,
        float $percentage,
        bool $active,
        ?string $description = null,
        ?TaxType $taxType = null

    ): self {
        return new self($id, $tax_code, $name, $tax_type_id, $percentage, $active, $description, $taxType);
    }

    // ========== GETTERS ==========

    public function getId(): int
    {
        return $this->id;
    }

    public function getTaxCode(): string
    {
        return $this->tax_code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaxTypeId(): int
    {
        return $this->tax_type_id;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function getTaxType(): ?TaxType
    {
        return $this->taxType;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    // ========== BUSINESS METHODS ==========

    /**
     * Actualizar la entidad con datos del DTO
     * Encapsula la lógica de actualización dentro de la entidad
     * 
     * @param UpdateTaxData $data
     * @return void
     */
    public function update(UpdateTaxData $data): void
    {
        $this->setName($data->name);
        $this->setDescription($data->description);
        $this->setPercentage($data->percentage);
    }

    // ========== SETTERS ==========

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Tax name cannot be empty');
        }
        $this->name = $name;
    }

    public function setPercentage(float $percentage): void
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Tax percentage must be between 0 and 100');
        }
        $this->percentage = $percentage;
    }

    public function setTaxTypeId(int $tax_type_id): void
    {
        if ($tax_type_id <= 0) {
            throw new \InvalidArgumentException('Tax type ID must be positive');
        }
        $this->tax_type_id = $tax_type_id;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}