<?php
namespace App\Domain\Auth\DTOs;

final readonly class UpdateUserData
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $role_ids = null,
        public ?array $department_ids = null
    ) {
        $this->validate();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
            role_ids: $data['role_ids'] ?? null,
            department_ids: $data['department_ids'] ?? null,
        );
    }


    public function toArray(): array
    {
        $result = [];

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }
        if ($this->email !== null) {
            $result['email'] = $this->email;
        }
        if ($this->password !== null) {
            $result['password'] = $this->password;
        }
        if ($this->role_ids !== null) {
            $result['role_ids'] = $this->role_ids;
        }

        if ($this->department_ids !== null) {
            $result['department_ids'] = $this->department_ids;
        }

        return $result;
    }

    public function validate(): void
    {
        // Only validate fields that are provided
        if ($this->name !== null && empty($this->name)) {
            throw new \InvalidArgumentException('name cannot be empty');
        }

        if ($this->email !== null) {
            if (empty($this->email)) {
                throw new \InvalidArgumentException('email cannot be empty');
            }
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('email is invalid');
            }
        }

        if ($this->password !== null && empty($this->password)) {
            throw new \InvalidArgumentException('password cannot be empty');
        }

        if ($this->role_ids !== null) {
            if (!is_array($this->role_ids)) {
                throw new \InvalidArgumentException('role_ids must be an array');
            }
            if (empty($this->role_ids)) {
                throw new \InvalidArgumentException('role_ids cannot be empty');
            }
        }

        if ($this->department_ids !== null) {
            if (!is_array($this->department_ids)) {
                throw new \InvalidArgumentException('department_ids must be an array');
            }
            if (empty($this->department_ids)) {
                throw new \InvalidArgumentException('department_ids cannot be empty');
            }
        }
    }








}