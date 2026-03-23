<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Scan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScansExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;
    private ?string $separator;
    private int $splitColumnsCount;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->separator = $this->resolveSeparator();
        $this->splitColumnsCount = $this->resolveSplitColumnsCount();
    }

    public function query()
    {
        return $this->baseQuery()->orderByDesc('scans.scanned_at');
    }

    private function baseQuery()
    {
        $query = Scan::query()
            ->with('user')
            ->leftJoin('table_assignments as ta', function ($join) {
                $join->on('ta.event_id', '=', 'scans.event_id')
                    ->on('ta.guest_name', '=', 'scans.value');
            })
            ->select('scans.*', DB::raw('ta.observations as assignment_observations'));

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

        return $query;
    }

    private function resolveSeparator(): ?string
    {
        $currentEventId = session('currentEvent');
        if (!$currentEventId) {
            return null;
        }

        $separator = Event::query()
            ->whereKey($currentEventId)
            ->value('separator');

        return filled($separator) ? (string) $separator : null;
    }

    private function resolveSplitColumnsCount(): int
    {
        if (!$this->separator) {
            return 0;
        }

        $values = (clone $this->baseQuery())->pluck('scans.value');
        $max = 0;

        foreach ($values as $value) {
            $partsCount = count(explode($this->separator, (string) $value));
            if ($partsCount > $max) {
                $max = $partsCount;
            }
        }

        return $max;
    }

    public function headings(): array
    {
        $headings = [
            'Lectura',
        ];

        if ($this->splitColumnsCount > 0) {
            for ($i = 1; $i <= $this->splitColumnsCount; $i++) {
                $headings[] = 'Parte ' . $i;
            }
        }

        return array_merge($headings, [
            'Tipo',
            'Observación',
            'Usuario',
            'Email',
            'Fecha / Hora',
        ]);
    }

    public function map($scan): array
    {
        $user = $scan->user;
        $datetime = $scan->scanned_at ?? $scan->created_at;
        $row = [$scan->value];

        if ($this->splitColumnsCount > 0) {
            $parts = explode($this->separator, (string) $scan->value);
            for ($i = 0; $i < $this->splitColumnsCount; $i++) {
                $row[] = trim($parts[$i] ?? '');
            }
        }

        return array_merge($row, [
            $scan->origin ?: Scan::ORIGIN_AUTOMATIC,
            $scan->assignment_observations ?? '',
            $user?->name ?? '',
            $user?->email ?? '',
            $datetime ? $datetime->format('Y-m-d H:i:s') : '',
        ]);
    }
}
