<?php
declare(strict_types=1);
namespace App\Domain\Family\Entities;

use App\Domain\Family\DTOs\CreateFamilyData;
use App\Domain\Family\DTOs\UpdateFamilyData;
use App\Domain\Family\Exceptions\DuplicateTaxTypeException;
use App\Domain\Family\Validation\FamilyTaxTypeValidator;
use App\Domain\Tax\Entities\Tax;

/**
 * Entidad Family del dominio
 * 
 * Representa una familia de productos en el sistema.
 * Utiliza DTOs para creación y primitivos para reconstrucción desde BD.
 */
final class Family
{
    private function __construct(
        private int $id,
        private string $name,
        private string $code,
        private ?string $description,
        private bool $active,
        private array $tax_ids = [],
        private array $taxes = [],
    ) {
      
    }

    /**
     * Factory method para crear nueva entidad (sin ID)
     * Usa el DTO CreateFamilyData que ya viene validado
     * 
     * @param CreateFamilyData $data DTO con los datos validados
     * @return self
     */

    public static function create(CreateFamilyData $data): self
    {
        return new self(
            id: 0, // El ID se asignará cuando se persista en la BD
            name: $data->name,
            code: $data->code,
            description: $data->description,
            active: true, // Por defecto las familias se crean activas
            tax_ids: $data->tax_ids,
            taxes: [], // Al crear, taxes viene vacío, se cargan después
        );
    }

    /**
     * Named constructor para reconstruir desde base de datos
     * Usa primitivos porque los datos ya están persistidos y validados
     * 
     * @param int $id
     * @param string $name
     * @param string $code
     * @param string|null $description
     * @param bool $active
     * @param array<Tax> $taxes Array de entidades Tax del dominio
     * @return self
     */
    public static function fromPrimitives(
        int $id,
        string $name,
        string $code,
        ?string $description,
        bool $active,
        array $taxes = []
    ): self {
        // Extraer tax_ids de los objetos Tax
        $tax_ids = array_map(fn(Tax $tax) => $tax->getId(), $taxes);

        return new self($id, $name, $code, $description, $active, $tax_ids, $taxes);
    }

    // ========== GETTERS ==========

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Retorna array de entidades Tax de dominio
     * @return array<Tax>
     */
    public function getTaxes(): array
    {
        return $this->taxes;
    }

    /**
     * Retorna solo los IDs de los taxes (útil para persistencia)
     * @return array<int>
     */
    public function getTaxIds(): array
    {
        return $this->tax_ids;
    }

    // ========== BUSINESS METHODS ==========

    /**
     * Actualizar la entidad con datos del DTO
     * Encapsula la lógica de actualización dentro de la entidad
     * 
     * @param UpdateFamilyData $data
     * @return void
     */
    public function update(UpdateFamilyData $data): void
    {
        $this->setName($data->name);
        $this->setDescription($data->description);
        $this->setTaxIds($data->tax_ids);
    }

    // ========== SETTERS ==========

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Family name cannot be empty');
        }
        $this->name = $name;
    }

    public function setCode(string $code): void
    {
        if (empty(trim($code))) {
            throw new \InvalidArgumentException('Family code cannot be empty');
        }
        $this->code = $code;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Establecer array de entidades Tax
     * También actualiza tax_ids automáticamente
     * @param array<Tax> $taxes
     */
    public function setTaxes(array $taxes): void
    {
        $this->taxes = $taxes;
        $this->tax_ids = array_map(fn(Tax $tax) => $tax->getId(), $taxes);
    }

    /**
     * Establecer taxes desde array de IDs
     * @param array<int> $tax_ids
     */
    public function setTaxIds(array $tax_ids): void
    {
        $this->tax_ids = $tax_ids;
        // Los objetos Tax se cargarán desde el repositorio si es necesario
    }


    // ========== VALIDATION ========== 
    public function validateTaxTypes(): void
    {
        $isValid = FamilyTaxTypeValidator::validate($this);
        if (!$isValid) {
            throw new DuplicateTaxTypeException($this->getId());
        }
    }
}

