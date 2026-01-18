<?php
declare(strict_types=1);

namespace App\Presentation\API\Warehouse\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:200',
            'address' => 'sometimes|required|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del deposito es obligatorio',
            'name.max' => 'El nombre del deposito no puede exceder :max caracteres',
            'address.required' => 'La direccion del deposito es obligatoria',
            'address.max' => 'La direccion del deposito no puede exceder :max caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'address' => 'direccion',
        ];
    }
}
