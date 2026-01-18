<?php
namespace App\Presentation\API\Department\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:departments,code',
            'description' => 'nullable|string|max:1000',
            'status' => 'sometimes|boolean',

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del departamento es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'code.required' => 'El código del departamento es obligatorio.',
            'code.unique' => 'El código del departamento ya existe en el sistema.',
            'code.max' => 'El código no debe exceder los 255 caracteres.',
            // 'description.required' => 'La descripción es obligatoria.',
            // 'description.max' => 'La descripción no debe exceder los 1000 caracteres.',
        ];
    }
}
