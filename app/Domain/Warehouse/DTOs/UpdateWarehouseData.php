<?php
declare(strict_types=1);
namespace App\Domain\Warehouse\DTOs;

final class UpdateWarehouseData
{
    public function __construct(
        public string $name,
        public string $address,
    ) {
        $this->validate();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            address: $data['address'] ?? throw new \InvalidArgumentException('address is required'),
        );
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address
        ];
    }

    public function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Warehouse name cannot be empty');
        }

        if (empty(trim($this->address))) {
            throw new \InvalidArgumentException('Warehouse address cannot be empty');
        }
    }
}