<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TableAssignmentsTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'ID',
            'QR',
            'observaciones',
        ];
    }

    public function array(): array
    {
        return [
            [1, 'Juan Perez', 'Sin TACC'],
            [2, 'Maria Gomez', 'Vegetariano'],
        ];
    }

    public function title(): string
    {
        return 'Modelo';
    }
}

