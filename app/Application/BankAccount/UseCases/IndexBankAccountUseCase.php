<?php
namespace App\Application\BankAccount\UseCases;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;

class IndexBankAccountUseCase
{
    public function __construct(
        private readonly BankAccountCrudRepository $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->index();
    }
}
