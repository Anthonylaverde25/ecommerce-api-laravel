<?php
namespace App\Presentation\API\Department\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del departamento es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'code.required' => 'El código del departamento es requerido',
            'code.string' => 'El código debe ser una cadena de texto',
            'code.max' => 'El código no puede exceder 50 caracteres',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción no puede exceder 500 caracteres',
        ];
    }
}
