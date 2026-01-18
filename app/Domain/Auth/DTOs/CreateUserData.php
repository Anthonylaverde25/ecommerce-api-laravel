<?php
declare(strict_types=1);
namespace App\Domain\Auth\DTOs;

final class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public array $role_ids,
        public array $department_ids,
    ) {
        $this->validate();
    }


    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            email: $data['email'] ?? throw new \InvalidArgumentException('email is required'),
            password: $data['password'] ?? throw new \InvalidArgumentException('password is required'),
            role_ids: $data['role_ids'] ?? throw new \InvalidArgumentException('role_ids is required'),
            department_ids: $data['department_ids'] ?? throw new \InvalidArgumentException('department_ids is required'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role_ids' => $this->role_ids,
            'department_ids' => $this->department_ids,
        ];
    }

    public function validate(): void
    {
        if (empty($this->name))
            throw new \InvalidArgumentException('name is required');
        if (empty($this->email))
            throw new \InvalidArgumentException('email is required');
        if (empty($this->role_ids))
            throw new \InvalidArgumentException('role_ids is required');

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('email is invalid');

        if (!is_array($this->role_ids))
            throw new \InvalidArgumentException('role_ids must be an array');
        if (!is_array($this->department_ids))
            throw new \InvalidArgumentException('department_ids must be an array');
    }


}