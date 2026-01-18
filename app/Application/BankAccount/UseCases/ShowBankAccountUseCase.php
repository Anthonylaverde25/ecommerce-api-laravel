<?php
namespace App\Application\BankAccount\UseCases;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;
use App\Domain\BankAccount\Entities\BankAccount;

class ShowBankAccountUseCase
{
    public function __construct(
        private readonly BankAccountCrudRepository $repository
    ) {
    }

    public function execute(int $id): BankAccount
    {
        return $this->repository->show($id);
    }
}
