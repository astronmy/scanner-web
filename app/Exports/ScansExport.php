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
        return $this->baseQuery()->orderByDesc('scans.scanned_at');
    }

    private function baseQuery()
    {
        $query = Scan::query()->with('user');

        if (session()->has('currentEvent')) {
            $query->where('scans.event_id', session('currentEvent'));
        }

        if (!empty($this->filters['value'])) {
            $query->where('scans.value', 'like', '%' . $this->filters['value'] . '%');
        }

        if (!empty($this->filters['user_id'])) {
            $query->where('scans.user_id', $this->filters['user_id']);
        }

        if (!empty($this->filters['from'])) {
            $query->whereDate('scans.scanned_at', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('scans.scanned_at', '<=', $this->filters['to']);
        }

        if (!empty($this->filters['origin']) && in_array($this->filters['origin'], [Scan::ORIGIN_MANUAL, Scan::ORIGIN_AUTOMATIC], true)) {
            $query->where('scans.origin', $this->filters['origin']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Lectura',
            'ID',
            'QR',
            'Observaciones',
            'Usuario',
            'Email',
            'Fecha / Hora',
            'Tipo',
        ];
    }

    public function map($scan): array
    {
        $user = $scan->user;
        $datetime = $scan->scanned_at ?? $scan->created_at;

        return [
            $scan->value,
            $scan->id_list ?? '',
            $scan->qr_list ?? '',
            $scan->observations ?? '',
            $user?->name ?? '',
            $user?->email ?? '',
            $datetime ? $datetime->format('Y-m-d H:i:s') : '',
            $scan->origin ?: Scan::ORIGIN_AUTOMATIC,
        ];
    }
}
