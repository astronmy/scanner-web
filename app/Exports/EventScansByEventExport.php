<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventScansByEventExport implements WithMultipleSheets
{
    public function __construct(private readonly Collection $events)
    {
    }

    public function sheets(): array
    {
        return $this->events
            ->map(fn (Event $event) => new EventScansSheet($event))
            ->all();
    }
}

