<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\BankAccount;

use App\Domain\BankAccount\Entities\BankAccount;
use App\Models\BankAccount as EloquentBankAccount;

class BankAccountMapper
{

    /**
     * @param EloquentBankAccount $eloquentBankAccount es un array procedente de la instancia de eloquent
     * @return BankAccount
     */
    public static function toDomain(EloquentBankAccount $eloquentBankAccount): BankAccount
    {
        return BankAccount::fromPrimitives(
            id: $eloquentBankAccount->id,
            name: $eloquentBankAccount->name,
            accountHolder: $eloquentBankAccount->account_holder,
            accountNumber: $eloquentBankAccount->account_number,
            isDefault: (bool) $eloquentBankAccount->is_default,
        );
    }


    public static function toEloquent(BankAccount $bankAccount): array
    {
        return [
            'name' => $bankAccount->name(),
            'account_holder' => $bankAccount->accountHolder(),
            'account_number' => $bankAccount->accountNumber(),
            'is_default' => $bankAccount->isDefault(),
        ];
    }
}
