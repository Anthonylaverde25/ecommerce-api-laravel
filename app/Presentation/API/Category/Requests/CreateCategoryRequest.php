<?php

declare(strict_types=1);

namespace Presentation\API\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CreateCategoryRequest - Presentation Layer
 * 
 * Validación de entrada HTTP para crear categoría
 */
class CreateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajusta según tu lógica de autorización
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'uuid', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.unique' => 'Ya existe una categoría con este nombre',
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'name.max' => 'El nombre no puede exceder 100 caracteres',
            'parent_id.uuid' => 'El ID de la categoría padre debe ser un UUID válido',
            'parent_id.exists' => 'La categoría padre no existe',
        ];
    }
}
