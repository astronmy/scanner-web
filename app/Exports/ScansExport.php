<?php

namespace App\Exports;

use App\Models\Scan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScansExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Scan::query()->with('user');

        if (!empty($this->filters['value'])) {
            $query->where('value', 'like', '%' . $this->filters['value'] . '%');
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        if (!empty($this->filters['from'])) {
            $query->whereDate('scanned_at', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('scanned_at', '<=', $this->filters['to']);
        }

        return $query->orderByDesc('scanned_at');
    }

    public function headings(): array
    {
        return [
            'Lectura',
            'Usuario',
            'Email',
            'Fecha / Hora',
        ];
    }

    public function map($scan): array
    {
        $user = $scan->user;
        $datetime = $scan->scanned_at ?? $scan->created_at;

        return [
            $scan->value,
            $user?->name ?? '',
            $user?->email ?? '',
            $datetime ? $datetime->format('Y-m-d H:i:s') : '',
        ];
    }
}
