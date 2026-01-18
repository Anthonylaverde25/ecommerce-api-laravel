<?php
namespace App\Domain\BankAccount\DTOs;


final class UpdateBankAccountData
{
    public function __construct(
        public string $name,
        public string $accountHolder,
        public string $accountNumber,
        public bool $isDefault = false,

    ) {
        $this->validate();
    }


    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? throw new \InvalidArgumentException('name is required'),
            accountHolder: $data['accountHolder'] ?? throw new \InvalidArgumentException('accountHolder is required'),
            accountNumber: $data['accountNumber'] ?? throw new \InvalidArgumentException('accountNumber is required'),
            isDefault: $data['isDefault'] ?? false,
        );
    }


    private function validate(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Bank account name cannot be empty');
        }

        if (empty(trim($this->accountHolder))) {
            throw new \InvalidArgumentException('Bank account account holder cannot be empty');
        }

        if (empty(trim($this->accountNumber))) {
            throw new \InvalidArgumentException('Bank account account number cannot be empty');
        }
    }
}