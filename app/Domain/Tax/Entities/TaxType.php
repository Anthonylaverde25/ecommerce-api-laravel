<?php
declare(strict_types=1);
namespace App\Domain\Tax\Entities;

final class TaxType
{
    private function __construct(
        private int $id,
        private string $name,
        private string $code,
        private bool $active,
    ) {
    }

    public static function fromPrimitives(
        int $id,
        string $name,
        string $code,
        bool $active,
    ): self {
        return new self($id, $name, $code, $active);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public static function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Name is required");
        }
    }

    public static function setCode(string $code): void
    {
        if (empty($code)) {
            throw new \InvalidArgumentException("Code is required");
        }
    }

    public static function setActive(bool $active): void
    {
        if (!is_bool($active)) {
            throw new \InvalidArgumentException("Active must be a boolean");
        }
    }

}