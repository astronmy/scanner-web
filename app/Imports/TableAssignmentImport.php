<?php

namespace App\Imports;

use App\Models\TableAssignment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TableAssignmentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        if (
            (!isset($row['mesa']) || $row['mesa'] === null) &&
            (!isset($row['nombre'])   || $row['nombre'] === null)
        ) {
            return null;
        }

        return new TableAssignment([
            'table_number' => $row['mesa'],
            'guest_name'   => $row['nombre'],
        ]);
    }
}
