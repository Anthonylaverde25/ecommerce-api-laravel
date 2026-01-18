<?php

namespace App\Presentation\API\BankAccount\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BankAccountCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'bank_accounts' => $this->collection->map(function ($bankAccount) {
                return [
                    'id' => $bankAccount->id(),
                    'name' => $bankAccount->name(),
                    'accountHolder' => $bankAccount->accountHolder(),
                    'accountNumber' => $bankAccount->accountNumber(),
                    'isDefault' => $bankAccount->isDefault(),
                ];
            })->values()->toArray(),
        ];
    }
}
