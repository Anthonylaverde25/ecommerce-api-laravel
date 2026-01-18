<?php
namespace App\Presentation\API\BankAccount\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bankAccountId = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'accountHolder' => 'required|string|max:255',
            'accountNumber' => "required|string|max:255|unique:bank_accounts,account_number,{$bankAccountId}",
            'isDefault' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la cuenta bancaria es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'accountHolder.required' => 'El titular de la cuenta es obligatorio.',
            'accountHolder.max' => 'El titular no debe exceder los 255 caracteres.',
            'accountNumber.required' => 'El número de cuenta es obligatorio.',
            'accountNumber.unique' => 'El número de cuenta ya existe en el sistema.',
            'accountNumber.max' => 'El número de cuenta no debe exceder los 255 caracteres.',
        ];
    }
}
