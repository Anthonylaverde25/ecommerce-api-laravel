<?php
namespace App\Application\BankAccount\Services;

use App\Application\BankAccount\UseCases\CreateBankAccountUseCase;
use App\Application\BankAccount\UseCases\IndexBankAccountUseCase;
use App\Application\BankAccount\UseCases\ShowBankAccountUseCase;
use App\Application\BankAccount\UseCases\UpdateBankAccountUseCase;
use App\Domain\BankAccount\DTOs\CreateBankAccountData;
use App\Domain\BankAccount\DTOs\UpdateBankAccountData;
use App\Domain\BankAccount\Entities\BankAccount;

class BankAccountService
{
    public function __construct(
        private readonly IndexBankAccountUseCase $indexBankAccountUseCase,
        private readonly ShowBankAccountUseCase $showBankAccountUseCase,
        private readonly CreateBankAccountUseCase $createBankAccountUseCase,
        private readonly UpdateBankAccountUseCase $updateBankAccountUseCase,
    ) {
    }

    public function index(): array
    {
        return $this->indexBankAccountUseCase->execute();
    }

    public function show(int $id): BankAccount
    {
        return $this->showBankAccountUseCase->execute($id);
    }

    public function create(CreateBankAccountData $data): BankAccount
    {
        return $this->createBankAccountUseCase->execute($data);
    }

    public function update(int $id, UpdateBankAccountData $data): BankAccount
    {
        return $this->updateBankAccountUseCase->execute($id, $data);
    }
}
