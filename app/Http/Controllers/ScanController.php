<?php

namespace App\Http\Controllers;

use App\Exports\ScansExport;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();
        $query = Scan::query()
            ->with('user')
            ->leftJoin('events as e', 'e.id', '=', 'scans.event_id')
            ->leftJoin('table_assignments as ta', function ($join) {
                $join->on('ta.event_id', '=', 'scans.event_id')
                    ->on('ta.guest_name', '=', 'scans.value');
            })
            ->select('scans.*', 'e.scan_type as event_scan_type', DB::raw('ta.observations as assignment_observations'));

        if (session()->has('currentEvent')) {
            $query->where('scans.event_id', session('currentEvent'));
        }

        if (! $authUser->isAdmin()) {
            $query->where('scans.user_id', $authUser->id);
        } elseif ($request->filled('user_id')) {
            $query->where('scans.user_id', $request->user_id);
        }

        if ($request->filled('value')) {
            $query->where('scans.value', 'like', '%' . $request->value . '%');
        }

        if ($request->filled('from')) {
            $query->whereDate('scans.scanned_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('scans.scanned_at', '<=', $request->to);
        }

        if ($request->filled('origin') && in_array($request->origin, [Scan::ORIGIN_MANUAL, Scan::ORIGIN_AUTOMATIC], true)) {
            $query->where('scans.origin', $request->origin);
        }

        $scans = $query
            ->orderByDesc('scans.scanned_at')
            ->paginate(20);

        $users = $authUser->isAdmin()
            ? User::orderBy('name')->get(['id', 'name', 'email'])
            : collect();

        return view('scans.index', compact('scans', 'users'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['value', 'user_id', 'from', 'to', 'origin']);
        if (! $request->user()->isAdmin()) {
            $filters['user_id'] = $request->user()->id;
        }

        $fileName = 'scans_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ScansExport($filters), $fileName);
    }

    public function edit(Scan $scan)
    {
        if (! request()->user()->isAdmin() && (int) $scan->user_id !== (int) request()->user()->id) {
            abort(403);
        }

        return view('scans.edit', compact('scan'));
    }

    public function update(Request $request, Scan $scan)
    {
        if (! $request->user()->isAdmin() && (int) $scan->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'observations' => ['nullable', 'string', 'max:500'],
            'scanned_at' => ['nullable', 'date'],
        ]);

        $observations = trim((string) ($data['observations'] ?? ''));

        $scan->update([
            'value' => trim($data['value']),
            'observations' => $observations !== '' ? $observations : null,
            'scanned_at' => $data['scanned_at'] ?? $scan->scanned_at,
        ]);

        return redirect()
            ->route('scans.index')
            ->with('success', 'Scan actualizado correctamente.');
    }

    public function destroy(Scan $scan)
    {
        if (! request()->user()->isAdmin() && (int) $scan->user_id !== (int) request()->user()->id) {
            abort(403);
        }

        $scan->delete();

        return redirect()
            ->back()
            ->with('success', 'Scan eliminado correctamente.');
    }
}
