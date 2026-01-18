<?php
namespace App\Domain\BankAccount\Entities;

use App\Domain\BankAccount\DTOs\CreateBankAccountData;
use App\Domain\BankAccount\DTOs\UpdateBankAccountData;


class BankAccount
{
    private function __construct(
        private int $id,
        private string $name,
        private string $accountHolder,
        private string $accountNumber,
        private bool $isDefault,
    ) {
    }


    public static function fromPrimitives(
        int $id,
        string $name,
        string $accountHolder,
        string $accountNumber,
        bool $isDefault
    ): self {
        return new self(
            id: $id,
            name: $name,
            accountHolder: $accountHolder,
            accountNumber: $accountNumber,
            isDefault: $isDefault
        );
    }





    // ===== GETTERS =======

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function accountHolder(): string
    {
        return $this->accountHolder;
    }

    public function accountNumber(): string
    {
        return $this->accountNumber;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }


    // ===== SETTERS =======

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setAccountHolder(string $accountHolder): void
    {
        $this->accountHolder = $accountHolder;
    }

    public function setAccountNumber(string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }


    public static function create(CreateBankAccountData $data): self
    {
        return new self(
            id: 0,
            name: $data->name,
            accountHolder: $data->accountHolder,
            accountNumber: $data->accountNumber,
            isDefault: $data->isDefault,
        );
    }

    public function update(UpdateBankAccountData $data): void
    {
        $this->setName($data->name);
        $this->setAccountHolder($data->accountHolder);
        $this->setAccountNumber($data->accountNumber);
        $this->setIsDefault($data->isDefault);
    }

}


