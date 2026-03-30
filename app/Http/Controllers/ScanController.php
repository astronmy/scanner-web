<?php

namespace App\Http\Controllers;

use App\Exports\ScansExport;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();
        $query = Scan::query()->with('user');

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }
        
        if ($request->filled('value')) {
            $query->where('value', 'like', '%' . $request->value . '%');
        }

        if ($authUser->isUser()) {
            $query->where('user_id', $authUser->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('scanned_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('scanned_at', '<=', $request->to);
        }

        if ($request->filled('origin') && in_array($request->origin, [Scan::ORIGIN_MANUAL, Scan::ORIGIN_AUTOMATIC], true)) {
            $query->where('origin', $request->origin);
        }

        $scans = $query
            ->orderByDesc('scanned_at')
            ->paginate(20);

        $users = $authUser->isUser()
            ? User::query()->whereKey($authUser->id)->get(['id', 'name', 'email'])
            : User::orderBy('name')->get(['id', 'name', 'email']);

        return view('scans.index', compact('scans', 'users'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['value', 'user_id', 'from', 'to', 'origin']);
        if ($request->user()->isUser()) {
            $filters['user_id'] = $request->user()->id;
        }

        $fileName = 'scans_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ScansExport($filters), $fileName);
    }

    public function edit(Scan $scan)
    {
        if ($scan->origin !== Scan::ORIGIN_MANUAL) {
            return redirect()
                ->route('scans.index')
                ->with('error', 'Solo se pueden editar scans manuales.');
        }

        return view('scans.edit', compact('scan'));
    }

    public function update(Request $request, Scan $scan)
    {
        if ($scan->origin !== Scan::ORIGIN_MANUAL) {
            return redirect()
                ->route('scans.index')
                ->with('error', 'Solo se pueden editar scans manuales.');
        }

        $data = $request->validate([
            'value' => ['required', 'string', 'max:255'],
            'scanned_at' => ['nullable', 'date'],
        ]);

        $scan->update([
            'value' => trim($data['value']),
            'scanned_at' => $data['scanned_at'] ?? $scan->scanned_at,
        ]);

        return redirect()
            ->route('scans.index')
            ->with('success', 'Scan manual actualizado correctamente.');
    }

    public function destroy(Scan $scan)
    {
        $scan->delete();

        return redirect()
            ->back()
            ->with('success', 'Scan eliminado correctamente.');
    }
}
