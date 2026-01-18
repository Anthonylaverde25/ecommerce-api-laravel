<?php
namespace App\Presentation\API\Family\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Preparar los datos antes de la validación
     * Extrae los datos de familyData si vienen anidados
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('familyData')) {
            $familyData = $this->input('familyData');

            // Si 'active' no viene en el payload, establecerlo como true por defecto
            if (!isset($familyData['active'])) {
                $familyData['active'] = true;
            }

            $this->merge($familyData);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:family,code',
            'description' => 'nullable|string|max:1000',
            'active' => 'sometimes|boolean',
            'tax_ids' => 'required|array|min:1',
            'tax_ids.*' => 'required|integer|exists:taxes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la familia es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'code.required' => 'El código de la familia es obligatorio.',
            'code.unique' => 'El código de la familia ya existe en el sistema.',
            'description.max' => 'La descripción no debe exceder los 1000 caracteres.',
            'tax_ids.required' => 'Debe seleccionar al menos un impuesto.',
            'tax_ids.min' => 'Debe seleccionar al menos un impuesto.',
            'tax_ids.*.exists' => 'Uno de los impuestos seleccionados no existe.',
        ];
    }
}