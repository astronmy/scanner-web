<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\TableAssignment;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function start(Request $request) {
        $scans = Scan::all()->count();
        $userScans = Scan::where('user_id', $request->user()->id)->count();
        $total = TableAssignment::all()->count();
        return view('scanners.start', compact('total', 'scans', 'userScans'));
    }
    public function storage(Request $request) {
        $search = TableAssignment::where('guest_name', $request->value)->first();

        if(!$search) {
            return response()->json([
                'message' => 'No se encuentra el registro '. $request->value
            ]);
        }

        $alreadyScan = Scan::where('value', $search->guest_name)->exists();

        if(! $alreadyScan) {
            Scan::create([
                'user_id'    => $request->user()->id,
                'value'      => $request->value,     
                'scanned_at' => now(),
            ]);
        }

        $scans = Scan::all()->count();
        $total = TableAssignment::all()->count();
        $userScans = Scan::where('user_id', $request->user()->id)->count();

        return response()->json([
                'location' => $search->table_number,
                'name' => $search->guest_name,
                'exists' => (int) $alreadyScan,
                'scans' => $scans,
                'total' => $total,
                'user_scans'=> $userScans
        ]);

    }
}
