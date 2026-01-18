<?php
namespace App\Presentation\API\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ahora usamos routing convencional con {id}
        $userId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|string|email|max:255|unique:users,email,{$userId}",
            'password' => 'sometimes|string|min:8|confirmed',
            'role_ids' => 'sometimes|array',
            'role_ids.*' => 'exists:roles,id',
            'department_ids' => 'sometimes|array',
            'department_ids.*' => 'exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre no debe exceder los 255 caracteres',
            'email.string' => 'El correo electrónico debe ser una cadena de texto',
            'email.email' => 'El correo electrónico debe ser un correo electrónico válido',
            'email.max' => 'El correo electrónico no debe exceder los 255 caracteres',
            'email.unique' => 'El correo electrónico ya existe',
            'password.string' => 'La contraseña debe ser una cadena de texto',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de contraseña no coincide',
            'role_ids.array' => 'Los roles deben ser un array',
            'role_ids.*.exists' => 'El rol seleccionado no existe',
        ];
    }
}
