<?php
namespace App\Application\BankAccount\UseCases;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;
use App\Domain\BankAccount\DTOs\UpdateBankAccountData;
use App\Domain\BankAccount\Entities\BankAccount;


class UpdateBankAccountUseCase
{
    public function __construct(
        private readonly BankAccountCrudRepository $repository
    ) {
    }

    public function execute(int $id, UpdateBankAccountData $data): BankAccount
    {
        $bankAccount = $this->repository->show($id);
        $bankAccount->update($data);
        return $this->repository->update($id, $bankAccount);

    }
}
