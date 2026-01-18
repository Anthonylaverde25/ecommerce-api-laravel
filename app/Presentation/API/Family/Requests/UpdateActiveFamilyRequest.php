<?php
namespace App\Presentation\API\Family\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateActiveFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [
            'active' => [
                'required',
                'boolean'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'active.required' => 'El estado de la familia es requerido',
            'active.boolean' => 'El estado de la familia debe ser un booleano'
        ];
    }

    public function attributes(): array
    {
        return [
            'active' => 'estado de la familia',
        ];
    }
}