<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Podés ajustar permisos después (ej: sólo admin)
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'                  => ['required', Rule::enum(RoleEnum::class)],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // eventos que puede ver/admin
            'events'                => ['array'],
            'events.*'              => ['integer', 'exists:events,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'nombre',
            'email'    => 'correo',
            'role'     => 'rol',
            'password' => 'contraseña',
        ];
    }
}
