<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportTableAssignmentRequest;
use App\Imports\TableAssignmentImport;
use App\Models\TableAssignment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TableAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = TableAssignment::query();

        if ($request->filled('table_number')) {
            $query->where('table_number', $request->table_number);
        }

        if ($request->filled('guest_name')) {
            $query->where('guest_name', 'like', '%' . $request->guest_name . '%');
        }

        $tableAssignments = $query
            ->orderBy('table_number')
            ->orderBy('guest_name')
            ->paginate(20);

        return view('table_assignments.index', compact('tableAssignments'));
    }

    public function import(ImportTableAssignmentRequest $request)
    {
        Excel::import(
            new TableAssignmentImport(),
            $request->file('file')
        );

        return redirect()->route('assignments.index');
    }

    public function importForm()
    {
        return view('table_assignments.import');
    }

    public function edit(TableAssignment $assignment)
    {
        return view('table_assignments.edit', compact('assignment'));
    }

    public function update(Request $request, TableAssignment $assignment)
    {
        $request->validate([
            'table_number' => ['required', 'integer', 'min:1'],
            'guest_name'   => ['required', 'string', 'max:255'],
        ]);

        $assignment->update([
            'table_number' => $request->table_number,
            'guest_name'   => $request->guest_name,
        ]);

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Ubicación actualizada correctamente.');
    }
}
