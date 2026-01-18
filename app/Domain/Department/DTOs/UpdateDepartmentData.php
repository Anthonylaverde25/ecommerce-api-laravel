<?php
namespace App\Domain\Department\DTOs;

final class UpdateDepartmentData
{
    public function __construct(
        public string $name,
        public string $code,
        public string $description,
        public string $status,
    ) {
        $this->validate();
    }



    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            code: $data['code'] ?? throw new \InvalidArgumentException('code is required'),
            description: $data['description'] ?? throw new \InvalidArgumentException('description is required'),
            status: $data['status'] ?? throw new \InvalidArgumentException('status is required'),
        );
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }

    public function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Department name cannot be empty');
        }

        if (empty(trim($this->code))) {
            throw new \InvalidArgumentException('Department code cannot be empty');
        }


        if (empty(trim($this->status))) {
            throw new \InvalidArgumentException('Department status cannot be empty');
        }
    }
}