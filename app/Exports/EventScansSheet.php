<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Scan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EventScansSheet implements FromCollection, WithHeadings, WithTitle
{
    private ?string $separator;
    private int $splitColumnsCount;

    public function __construct(private readonly Event $event)
    {
        $this->separator = filled($this->event->separator) ? (string) $this->event->separator : null;
        $this->splitColumnsCount = $this->resolveSplitColumnsCount();
    }

    public function collection()
    {
        return Scan::query()
            ->with('user')
            ->leftJoin('table_assignments as ta', function ($join) {
                $join->on('ta.event_id', '=', 'scans.event_id')
                    ->on('ta.guest_name', '=', 'scans.value');
            })
            ->select('scans.*', DB::raw('ta.observations as assignment_observations'))
            ->where('scans.event_id', $this->event->id)
            ->orderByDesc('scans.scanned_at')
            ->get()
            ->map(function (Scan $scan) {
                $user = $scan->user;
                $datetime = $scan->scanned_at ?? $scan->created_at;
                $row = [
                    'lectura' => $scan->value,
                ];

                if ($this->splitColumnsCount > 0) {
                    $parts = explode($this->separator, (string) $scan->value);
                    for ($i = 0; $i < $this->splitColumnsCount; $i++) {
                        $row['parte_' . ($i + 1)] = trim($parts[$i] ?? '');
                    }
                }

                return array_merge($row, [
                    'tipo' => $scan->origin ?: Scan::ORIGIN_AUTOMATIC,
                    'observacion' => $scan->observations ?? $scan->assignment_observations ?? '',
                    'usuario' => $user?->name ?? '',
                    'email' => $user?->email ?? '',
                    'fecha_hora' => $datetime ? $datetime->format('Y-m-d H:i:s') : '',
                ]);
            });
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

    public function title(): string
    {
        $clean = preg_replace('/[\\\\\\/?*\\[\\]:]/', '', $this->event->name) ?: 'Evento';
        return mb_substr($clean, 0, 31);
    }

    private function resolveSplitColumnsCount(): int
    {
        if (!$this->separator) {
            return 0;
        }

        $values = Scan::query()
            ->where('event_id', $this->event->id)
            ->pluck('value');

        $max = 0;
        foreach ($values as $value) {
            $partsCount = count(explode($this->separator, (string) $value));
            if ($partsCount > $max) {
                $max = $partsCount;
            }
        }

        return $max;
    }
}

