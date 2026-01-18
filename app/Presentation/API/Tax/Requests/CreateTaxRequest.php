<?php

namespace App\Presentation\API\Tax\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest para validar la creación de un Tax
 * 
 * Esta validación ocurre a nivel HTTP (Infrastructure Layer)
 * Valida formato, tipos y reglas de base de datos
 */
class CreateTaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Implementar autorización según roles/permisos
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Código del impuesto (debe ser único)
            'tax_code' => [
                'required',
                'string',
                'max:50',
                'unique:taxes,tax_code'
            ],

            // Nombre del impuesto
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],

            // ID del tipo de impuesto (debe existir en tax_types)
            'tax_type_id' => [
                'required',
                'integer',
                'exists:tax_types,id'
            ],

            // Porcentaje del impuesto
            'percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            // Descripción del impuesto (opcional)
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // tax_code
            'tax_code.required' => 'El código del impuesto es obligatorio.',
            'tax_code.string' => 'El código del impuesto debe ser una cadena de texto.',
            'tax_code.max' => 'El código del impuesto no debe exceder los 50 caracteres.',
            'tax_code.unique' => 'El código del impuesto ya existe en el sistema.',

            // name
            'name.required' => 'El nombre del impuesto es obligatorio.',
            'name.string' => 'El nombre del impuesto debe ser una cadena de texto.',
            'name.min' => 'El nombre del impuesto debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre del impuesto no debe exceder los 255 caracteres.',

            // tax_type_id
            'tax_type_id.required' => 'El tipo de impuesto es obligatorio.',
            'tax_type_id.integer' => 'El tipo de impuesto debe ser un número entero.',
            'tax_type_id.exists' => 'El tipo de impuesto seleccionado no existe.',

            // percentage
            'percentage.required' => 'El porcentaje del impuesto es obligatorio.',
            'percentage.numeric' => 'El porcentaje del impuesto debe ser un número.',
            'percentage.min' => 'El porcentaje del impuesto debe ser mayor o igual a 0.',
            'percentage.max' => 'El porcentaje del impuesto no debe exceder el 100%.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tax_code' => 'código de impuesto',
            'name' => 'nombre',
            'tax_type_id' => 'tipo de impuesto',
            'percentage' => 'porcentaje',
        ];
    }
}
