<?php
declare(strict_types=1);
namespace App\Domain\Warehouse\Entities;

use App\Domain\Warehouse\DTOs\CreateWarehouseData;
use App\Domain\Warehouse\DTOs\UpdateWarehouseData;

final class Warehouse
{
    private function __construct(
        private int $id,
        private string $name,
        private string $address
    ) {
    }



    /**
     * Summary of create
     * @param CreateWarehouseData $data
     * @return self
     */
    public static function create(CreateWarehouseData $data): self
    {
        return new self(
            id: 0,
            name: $data->name,
            address: $data->address,
        );
    }



    public static function fromPrimitives(
        int $id,
        string $name,
        string $address
    ): self {
        return new self($id, $name, $address);
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

    public function getAddress(): string
    {
        return $this->address;
    }

    // ========== SETTERS ==========

    public function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException('Warehouse name cannot be empty');
        }
        $this->name = $name;
    }

    public function setAddress(string $address): void
    {
        if (empty(trim($address))) {
            throw new \InvalidArgumentException('Warehouse address cannot be empty');
        }
        $this->address = $address;
    }

    // ========== BUSINESS METHODS ==========

    /**
     * Actualizar la entidad con datos del DTO
     * Encapsula la lógica de actualización dentro de la entidad (DDD)
     */
    public function update(UpdateWarehouseData $data): void
    {
        $this->setName($data->name);
        $this->setAddress($data->address);
    }





}