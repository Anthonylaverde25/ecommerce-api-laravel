<?php
declare(strict_types=1);

namespace App\Domain\Role\DTOs;

final readonly class CreateRoleData
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public array $permissions = []
    ) {
        $this->validate();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            description: $data['description'] ?? null,
            permissions: $data['permissions'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'permissions' => $this->permissions,
        ];
    }

    private function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Role name cannot be empty');
        }
    }
}