<?php
namespace App\Domain\Department\Entities;

use App\Domain\Department\DTOs\CreateDepartmentData;
use App\Domain\Department\DTOs\UpdateDepartmentData;

class Department
{
    private function __construct(
        public int $id,
        public string $name,
        public string $code,
        public ?string $description,
        public bool $status,
    ) {
        $this->validate();
    }



    public static function fromPrimitives(
        int $id,
        string $name,
        string $code,
        ?string $description,
        bool $status,
    ) {
        return new self(
            id: $id,
            name: $name,
            code: $code,
            description: $description,
            status: $status,
        );
    }


    public static function create(CreateDepartmentData $data): self
    {
        return new self(
            id: 0,
            name: $data->name,
            code: $data->code,
            description: $data->description,
            status: $data->status,
        );
    }


    // ========== GETTERS ========== 

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function status(): bool
    {
        return $this->status;
    }


    // ========== SETTERS ========== 
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if (empty(trim($this->code))) {
            throw new \InvalidArgumentException('Code cannot be empty');
        }


    }

    public function update(UpdateDepartmentData $data): void
    {
        $this->setName($data->name);
        $this->setCode($data->code);
        $this->setDescription($data->description);
        $this->setStatus($data->status);

    }

}