<?php
declare(strict_types=1);
namespace App\Domain\Tax\DTOs;

final class UpdateTaxData
{
    public function __construct(
        public string $name,
        public string $description,
        public float $percentage

    ) {
        $this->validate();
    }


    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            description: $data['description'] ?? throw new \InvalidArgumentException('description is required'),
            percentage: (float) ($data['percentage'] ?? throw new \InvalidArgumentException('percentage is required'))
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'percentage' => $this->percentage
        ];
    }

    public function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Tax name cannot be empty');
        }

        if (empty(trim($this->description))) {
            throw new \InvalidArgumentException('Tax description cannot be empty');
        }

        if ($this->percentage < 0 || $this->percentage > 100) {
            throw new \InvalidArgumentException('Tax percentage must be between 0 and 100');
        }
    }
}