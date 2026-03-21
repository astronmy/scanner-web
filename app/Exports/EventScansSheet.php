<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Scan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EventScansSheet implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(private readonly Event $event)
    {
    }

    public function collection()
    {
        return Scan::query()
            ->with('user')
            ->where('event_id', $this->event->id)
            ->orderByDesc('scanned_at')
            ->get()
            ->map(function (Scan $scan) {
                $user = $scan->user;
                $datetime = $scan->scanned_at ?? $scan->created_at;

                return [
                    'lectura' => $scan->value,
                    'usuario' => $user?->name ?? '',
                    'email' => $user?->email ?? '',
                    'fecha_hora' => $datetime ? $datetime->format('Y-m-d H:i:s') : '',
                ];
            });
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

    public function title(): string
    {
        $clean = preg_replace('/[\\\\\\/?*\\[\\]:]/', '', $this->event->name) ?: 'Evento';
        return mb_substr($clean, 0, 31);
    }
}

