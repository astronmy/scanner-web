<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportTableAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajustá si luego usás policies
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xls,xlsx'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'The file is required.',
            'file.file'     => 'The uploaded file is not valid.',
            'file.mimes'    => 'The file must be an Excel file (xls or xlsx).',
        ];
    }
}
