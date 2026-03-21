<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'new_button_enabled' => ['required', 'boolean'],
            'message_not_found' => ['nullable', 'string', 'max:255'],
            'scan_type' => ['required', 'integer', 'in:1,2'],
            'autostart' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'new_button_enabled' => $this->boolean('new_button_enabled'),
            'autostart' => $this->boolean('autostart'),
            'scan_type' => (int) ($this->input('scan_type', 1)),
        ]);
    }

    public function attributes(): array
    {
        return [
            'name'       => 'denominación',
            'start_date' => 'fecha desde',
            'end_date'   => 'fecha hasta',
            'new_button_enabled' => 'mostrar botón nuevo en escáner',
            'message_not_found' => 'mensaje de escáner',
            'scan_type' => 'tipo de escaneo',
            'autostart' => 'iniciar automáticamente',
        ];
    }
}
