<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'label'       => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'new_button_enabled' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'new_button_enabled' => $this->boolean('new_button_enabled'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'name'       => 'denominación',
            'label'       => 'etiqueta',
            'start_date' => 'fecha desde',
            'end_date'   => 'fecha hasta',
            'new_button_enabled' => 'mostrar botón nuevo en escáner',
        ];
    }
}
