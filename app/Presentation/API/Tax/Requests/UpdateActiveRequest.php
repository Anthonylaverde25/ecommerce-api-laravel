<?php

namespace App\Presentation\API\Tax\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest para validar la creación de un Tax
 * 
 * Esta validación ocurre a nivel HTTP (Infrastructure Layer)
 * Valida formato, tipos y reglas de base de datos
 */
class UpdateActiveRequest extends FormRequest
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
            // Estado activo del impuesto (el tax_id viene por parámetro de ruta)
            'active' => [
                'required',
                'boolean',
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
            'active.required' => 'El estado del impuesto es obligatorio.',
            'active.boolean' => 'El estado del impuesto debe ser un booleano.',
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
            'active' => 'estado del impuesto',
        ];
    }
}
