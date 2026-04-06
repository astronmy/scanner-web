<?php

namespace App\Imports;

use App\Models\TableAssignment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableAssignmentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        $tableNumber = $row['mesa'] ?? $row['listado'] ?? $row['id'] ?? null;
        $guestName = $row['nombre'] ?? $row['qr'] ?? null;

        if (($tableNumber === null || $tableNumber === '') && ($guestName === null || $guestName === '')) {
            return null;
        }

        $eventId =  session('currentEvent') ?? null;
        $observations = $row['observaciones'] ?? $row['observations'] ?? null;

        return new TableAssignment([
            'table_number' => $tableNumber,
            'guest_name'   => $guestName,
            'observations' => $observations ?: null,
            'event_id' => $eventId
        ]);
    }
}
