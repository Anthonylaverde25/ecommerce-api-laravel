<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories\BankAccount;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;
use App\Domain\BankAccount\Entities\BankAccount;
use App\Infrastructure\Persistence\Eloquent\Mappers\BankAccount\BankAccountMapper;
use App\Models\BankAccount as EloquentBankAccount;

class EloquentBankAccountRepository implements BankAccountCrudRepository
{
    public function index(): array
    {
        $bankAccounts = EloquentBankAccount::all();
        return $bankAccounts->map(fn($b) => BankAccountMapper::toDomain($b))->values()->all();
    }

    public function show(int $id): BankAccount
    {
        $bankAccount = EloquentBankAccount::find($id);
        return BankAccountMapper::toDomain($bankAccount);
    }

    public function create(BankAccount $data): BankAccount
    {
        $eloquentBankAccount = EloquentBankAccount::create(BankAccountMapper::toEloquent($data));
        return BankAccountMapper::toDomain($eloquentBankAccount);
    }

    public function update(int $id, BankAccount $data): BankAccount
    {
        $eloquentBankAccount = EloquentBankAccount::findOrFail($id);
        $eloquentBankAccount->update(BankAccountMapper::toEloquent($data));
        return BankAccountMapper::toDomain($eloquentBankAccount);
    }
}
