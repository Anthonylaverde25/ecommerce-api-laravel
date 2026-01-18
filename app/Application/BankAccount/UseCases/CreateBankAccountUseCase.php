<?php
namespace App\Application\BankAccount\UseCases;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;
use App\Domain\BankAccount\DTOs\CreateBankAccountData;
use App\Domain\BankAccount\Entities\BankAccount;

class CreateBankAccountUseCase
{
    public function __construct(
        private readonly BankAccountCrudRepository $repository
    ) {
    }

    public function execute(CreateBankAccountData $data): BankAccount
    {
        $bankAccount = BankAccount::create($data);
        return $this->repository->create($bankAccount);
    }
}
