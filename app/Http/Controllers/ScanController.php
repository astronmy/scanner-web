<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\User;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $query = Scan::query()->with('user');

        if ($request->filled('value')) {
            $query->where('value', 'like', '%' . $request->value . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('scanned_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('scanned_at', '<=', $request->to);
        }

        $scans = $query
            ->orderByDesc('scanned_at')
            ->paginate(20);

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('scans.index', compact('scans', 'users'));
    }
}
