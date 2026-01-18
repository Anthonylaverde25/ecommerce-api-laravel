<?php
declare(strict_types=1);

namespace App\Presentation\API\Tax\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|min:2|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'percentage' => 'sometimes|required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre no puede estar vacío.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'percentage.numeric' => 'El porcentaje debe ser un número.',
            'percentage.min' => 'El porcentaje no puede ser menor a 0.',
            'percentage.max' => 'El porcentaje no puede ser mayor a 100.',
        ];
    }
}
