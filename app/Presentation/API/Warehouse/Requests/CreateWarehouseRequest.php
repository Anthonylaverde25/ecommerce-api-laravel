<?php
namespace App\Presentation\API\Warehouse\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CreateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:200',
            'address' => 'required|string|max:200',
        ];
    }

    public function message(): array
    {
        return [
            'name.required' => 'El nombre del deposito es obligatorio',
            'address.required' => 'La direccion del deposito es obligatoria',
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