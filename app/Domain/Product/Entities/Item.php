<?php
declare(strict_types=1);

namespace Domain\Product\Entities;

use JsonSerializable;

final class Item implements JsonSerializable
{
    public function __construct(
        private readonly int $id,
        private string $name,
        private ?string $description,
        private float $price,
        private ?float $cost_price,
        private bool $is_active,
        private int $stock = 0
    ) {
    }

    /**
     * Getters
     */
    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function costPrice(): ?float
    {
        return $this->cost_price;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function stock(): int
    {
        return $this->stock;
    }

    /**
     * Serialización a JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'is_active' => $this->is_active,
            'stock' => $this->stock,
        ];
    }
}
