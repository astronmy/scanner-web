<?php

namespace App\Http\Controllers;

use App\Exports\TableAssignmentsListExport;
use App\Exports\TableAssignmentsTemplateExport;
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

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }

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

    public function export(Request $request)
    {
        $filters = $request->only(['table_number', 'guest_name']);
        $suffix = session('currentEvent') ? 'event_' . session('currentEvent') : 'sin_evento';
        $fileName = 'listado_' . $suffix . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new TableAssignmentsListExport($filters), $fileName);
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

    public function downloadTemplate()
    {
        $fileName = 'modelo_importacion_listado.xlsx';
        return Excel::download(new TableAssignmentsTemplateExport(), $fileName);
    }

    public function edit(TableAssignment $assignment)
    {
        return view('table_assignments.edit', compact('assignment'));
    }

    public function update(Request $request, TableAssignment $assignment)
    {
        $request->validate([
            'table_number' => ['required', 'string', 'max:50'],
            'guest_name'   => ['required', 'string', 'max:255'],
        ]);

        $assignment->update([
            'table_number' => $request->table_number,
            'guest_name'   => $request->guest_name,
            'event_id' => session('currentEvent') ?? null
        ]);

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Ubicación actualizada correctamente.');
    }

    public function destroy(TableAssignment $assignment)
    {
        if (session()->has('currentEvent') && (int) $assignment->event_id !== (int) session('currentEvent')) {
            return redirect()
                ->route('assignments.index')
                ->with('error', 'No podés eliminar un registro de otro evento.');
        }

        $assignment->delete();

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    public function destroyAll(Request $request)
    {
        if (!session()->has('currentEvent')) {
            return redirect()
                ->route('assignments.index')
                ->with('error', 'Seleccioná un evento antes de eliminar masivamente.');
        }

        $deleted = TableAssignment::query()
            ->where('event_id', session('currentEvent'))
            ->delete();

        return redirect()
            ->route('assignments.index')
            ->with('success', "Se eliminaron {$deleted} registros del listado.");
    }
}
