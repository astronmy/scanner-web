<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Scan;
use App\Models\TableAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $event = Event::findOrFail(session('currentEvent'));
        $label = $event->label;
        $newButtonEnabled = (bool) $event->new_button_enabled;
        $messageNotFound = $event->message_not_found ?: 'La persona ya ingresó previamente';
        $autoStartEnabled = (bool) ($event->autostart ?? false);

        return view('scanners.start', compact('total', 'scans', 'userScans', 'label', 'newButtonEnabled', 'messageNotFound', 'autoStartEnabled'));
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

        $event = Event::findOrFail(session('currentEvent'));
        $checkDuplicity = (bool) ($event->check_duplicity ?? false);

        $checkQry = Scan::query();

        if (session()->has('currentEvent')) {
            $checkQry->where('event_id', session('currentEvent'));
        }

        $alreadyScan = $checkQry->where('value', $search->guest_name)->exists();

        $confirmDuplicate = $request->boolean('confirm_duplicate');

        if ($alreadyScan && $checkDuplicity && !$confirmDuplicate) {
            $scans = Scan::where('event_id', session('currentEvent'))->count();
            $total = TableAssignment::where('event_id', session('currentEvent'))->count();
            $userScans = Scan::where('event_id', session('currentEvent'))
                ->where('user_id', $request->user()->id)
                ->count();

            return response()->json([
                'location' => $search->table_number,
                'name' => $search->guest_name,
                'exists' => 1,
                'requires_confirmation' => true,
                'message' => 'Desea agregarlo nuevamente?',
                'scans' => $scans,
                'total' => $total,
                'user_scans' => $userScans,
            ]);
        }

        if(! $alreadyScan || ($alreadyScan && $checkDuplicity && $confirmDuplicate)) {
            Scan::create([
                'user_id'    => $request->user()->id,
                'event_id'   => session('currentEvent'),
                'value'      => $request->value,     
                'scanned_at' => now(),
                'origin'     => Scan::ORIGIN_AUTOMATIC,
            ]);
        }

        $scans = Scan::where('event_id', session('currentEvent'))->count();
        $total = TableAssignment::where('event_id', session('currentEvent'))->count();
        $userScans = Scan::where('event_id', session('currentEvent'))->where('user_id', $request->user()->id)->count();

        return response()->json([
                'location' => $search->table_number,
                'name' => $search->guest_name,
                'exists' => (int) $alreadyScan,
                'requires_confirmation' => false,
                'scans' => $scans,
                'total' => $total,
                'user_scans'=> $userScans
        ]);

    }

    public function storeManual(Request $request)
    {
        if (!session()->has('currentEvent')) {
            return response()->json([
                'message' => 'Seleccioná un evento antes de cargar manualmente.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'value' => ['required', 'string', 'max:255'],
            'observation' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();
        $value = trim($data['value']);
        $observation = trim((string) ($data['observation'] ?? ''));

        $scan = Scan::create([
            'user_id' => $request->user()->id,
            'event_id' => session('currentEvent'),
            'value' => $value,
            'scanned_at' => now(),
            'origin' => Scan::ORIGIN_MANUAL,
        ]);

        $scans = Scan::where('event_id', session('currentEvent'))->count();
        $total = TableAssignment::where('event_id', session('currentEvent'))->count();
        $userScans = Scan::where('event_id', session('currentEvent'))
            ->where('user_id', $request->user()->id)
            ->count();

        return response()->json([
            'message' => 'Scan manual cargado correctamente.',
            'name' => $value,
            'location' => $observation !== '' ? $observation : '—',
            'exists' => 0,
            'scans' => $scans,
            'total' => $total,
            'user_scans' => $userScans,
            'scan_id' => $scan->id,
            'origin' => $scan->origin,
        ]);
    }
}
