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
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'label'       => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'new_button_enabled' => ['required', 'boolean'],
            'message_not_found' => ['nullable', 'string', 'max:255'],
            'scan_type' => ['required', 'integer', 'in:1,2,3'],
            'autostart' => ['required', 'boolean'],
            'separator' => ['nullable', 'string', 'max:20'],
            'check_duplicity' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'new_button_enabled' => $this->boolean('new_button_enabled'),
            'autostart' => $this->boolean('autostart'),
            'check_duplicity' => $this->boolean('check_duplicity'),
            'scan_type' => (int) ($this->input('scan_type', 1)),
        ]);
    }

    public function attributes(): array
    {
        return [
            'name'       => 'denominación',
            'cover_image' => 'imagen del evento',
            'label'       => 'etiqueta',
            'start_date' => 'fecha desde',
            'end_date'   => 'fecha hasta',
            'new_button_enabled' => 'mostrar botón nuevo en escáner',
            'message_not_found' => 'mensaje de escáner',
            'scan_type' => 'tipo de escaneo',
            'autostart' => 'iniciar automáticamente',
            'separator' => 'separador',
            'check_duplicity' => 'chequear duplicidad',
        ];
    }
}
