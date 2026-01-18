<?php
declare(strict_types=1);
namespace App\Domain\Warehouse\DTOs;

use phpDocumentor\Reflection\Types\Self_;

final readonly class CreateWarehouseData
{
    public function __construct(
        public string $name,
        public string $address
    ) {
        $this->validate();
    }


    /**
     * @param array $data
     * @return self
     * @throws \InvalidArgumentException
     */

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            address: $data['address'] ?? throw new \InvalidArgumentException('address is required'),
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
            'address' => $this->address,
        ];
    }


    private function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Warehouse name cannot be empty');
        }

        if (empty(trim($this->address))) {
            throw new \InvalidArgumentException('Warehouse address cannot be empty');
        }
    }


}