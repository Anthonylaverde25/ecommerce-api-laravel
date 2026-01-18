<?php
namespace App\Presentation\API\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no debe exceder los 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.string' => 'El correo electrónico debe ser una cadena de texto',
            'email.email' => 'El correo electrónico debe ser un correo electrónico válido',
            'email.max' => 'El correo electrónico no debe exceder los 255 caracteres',
            'email.unique' => 'El correo electrónico ya existe',
            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'La contraseña debe ser una cadena de texto',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ];
    }
}