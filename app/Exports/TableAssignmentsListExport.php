<?php

namespace App\Exports;

use App\Models\TableAssignment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TableAssignmentsListExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private array $filters = [],
        private bool $includeObservations = true,
    ) {
    }

    public function query()
    {
        $query = TableAssignment::query()
            ->orderBy('table_number')
            ->orderBy('guest_name');

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }

        if (! empty($this->filters['table_number'])) {
            $query->where('table_number', $this->filters['table_number']);
        }

        if (! empty($this->filters['guest_name'])) {
            $query->where('guest_name', 'like', '%' . $this->filters['guest_name'] . '%');
        }

        return $query;
    }

    public function headings(): array
    {
        $headings = [
            'ID',
            'Listado',
            'QR',
        ];

        if ($this->includeObservations) {
            $headings[] = 'Observaciones';
        }

        $headings[] = 'Creado';

        return $headings;
    }

    public function map($row): array
    {
        $rowData = [
            $row->id,
            $row->table_number,
            $row->guest_name,
        ];

        if ($this->includeObservations) {
            $rowData[] = $row->observations ?? '';
        }

        $rowData[] = $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';

        return $rowData;
    }
}
