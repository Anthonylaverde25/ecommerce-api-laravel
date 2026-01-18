<?php
declare(strict_types=1);
namespace App\Domain\Department\DTOs;
final class CreateDepartmentData
{
    public function __construct(
        public string $name,
        public string $code,
        public ?string $description = null,
        public bool $status = true,
    ) {
        $this->validate();
    }


    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            code: $data['code'] ?? throw new \InvalidArgumentException('code is required'),
            description: $data['description'] ?? null,
            status: $data['status'] ?? throw new \InvalidArgumentException('status is required'),
        );
    }



    private function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Family name cannot be empty');
        }

        if (empty(trim($this->code))) {
            throw new \InvalidArgumentException('Family code cannot be empty');
        }


    }
}
