<?php
declare(strict_types=1);

namespace App\Presentation\API\Family\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|min:2|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'tax_ids' => 'sometimes|nullable|array',
            'tax_ids.*' => 'integer|exists:taxes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre no puede estar vacío.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'tax_ids.array' => 'tax_ids debe ser un array.',
            'tax_ids.*.integer' => 'Cada tax_id debe ser un número entero.',
            'tax_ids.*.exists' => 'El tax_id :input no existe en la base de datos.',
        ];
    }
}
