<?php
namespace App\Presentation\API\BankAccount\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountDetailsResource extends JsonResource
{
    public static $wrap = 'bank_account';

    public function toArray(Request $request): array
    {
        $bankAccount = $this->resource;

        return [
            'id' => $bankAccount->id(),
            'name' => $bankAccount->name(),
            'account_holder' => $bankAccount->accountHolder(),
            'account_number' => $bankAccount->accountNumber(),
            'is_default' => $bankAccount->isDefault()
        ];
    }


}
