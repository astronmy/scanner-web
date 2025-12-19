<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? null;

        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role'                  => ['required', Rule::enum(RoleEnum::class)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
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
