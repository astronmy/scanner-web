<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Scan;
use App\Models\TableAssignment;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function start(Request $request) {
        $scansQuery = Scan::query();
       
        if (session()->has('currentEvent')) {
            $scansQuery->where('event_id', session('currentEvent'));
        }

        $scans = $scansQuery->count();

        $userScansQuery = Scan::query();

        if (session()->has('currentEvent')) {
            $userScansQuery->where('event_id', session('currentEvent'));
        }

        $userScans = $userScansQuery->where('user_id', $request->user()->id)->count();

        $query = TableAssignment::query();

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }

        $total = $query->count();

        $event = Event::findOFail(session('currentEvent'));
        $label = $event->label;

        return view('scanners.start', compact('total', 'scans', 'userScans', 'label'));
    }
    public function storage(Request $request) {

        $query = TableAssignment::query();

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }

        $search = $query->where('guest_name', $request->value)->first();

        if(!$search) {
            return response()->json([
                'message' => 'No se encuentra el registro '. $request->value
            ]);
        }

        $checkQry = Scan::query();

        if (session()->has('currentEvent')) {
            $checkQry->where('event_id', session('currentEvent'));
        }

        $alreadyScan = $checkQry->where('value', $search->guest_name)->exists();

        if(! $alreadyScan) {
            Scan::create([
                'user_id'    => $request->user()->id,
                'value'      => $request->value,     
                'scanned_at' => now(),
            ]);
        }

        $scans = Scan::where('event_id', session('currentEvent'))->count();
        $total = TableAssignment::where('event_id', session('currentEvent'))->count();
        $userScans = Scan::where('event_id', session('currentEvent'))->where('user_id', $request->user()->id)->count();

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
