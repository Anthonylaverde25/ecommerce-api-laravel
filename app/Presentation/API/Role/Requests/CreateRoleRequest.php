<?php
declare(strict_types=1);

namespace App\Presentation\API\Role\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CreateRoleRequest - Presentation Layer
 * 
 * Validates incoming HTTP request for role creation
 */
class CreateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // TODO: Implement authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
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
            'name.required' => 'El nombre del rol es requerido',
            'name.unique' => 'Ya existe un rol con este nombre',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'description.max' => 'La descripción no puede exceder 500 caracteres',
            'permissions.array' => 'Los permisos deben ser un array',
        ];
    }
}
