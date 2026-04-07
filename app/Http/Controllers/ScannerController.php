<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Scan;
use App\Models\TableAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $event = Event::findOrFail(session('currentEvent'));
        $isStorageType = (int) ($event->scan_type ?? 1) === 2;
        $query = TableAssignment::query();

        if (session()->has('currentEvent')) {
            $query->where('event_id', session('currentEvent'));
        }

        $total = $isStorageType ? $scans : $query->count();
        $label = $event->label;
        $newButtonEnabled = (bool) $event->new_button_enabled;
        $messageNotFound = $event->message_not_found ?: 'La persona ya ingresó previamente';
        $autoStartEnabled = (bool) ($event->autostart ?? false);

        return view('scanners.start', compact('total', 'scans', 'userScans', 'label', 'newButtonEnabled', 'messageNotFound', 'autoStartEnabled', 'isStorageType'));
    }
    public function storage(Request $request) {
        $event = Event::findOrFail(session('currentEvent'));
        $isStorageType = (int) ($event->scan_type ?? 1) === 2;
        $checkDuplicity = (bool) ($event->check_duplicity ?? false);
        $scannedValue = (string) $request->value;

        $search = null;
        if (! $isStorageType) {
            $query = TableAssignment::query();

            if (session()->has('currentEvent')) {
                $query->where('event_id', session('currentEvent'));
            }

            $search = $query->where('guest_name', $scannedValue)->first();

            if (! $search) {
                return response()->json([
                    'message' => 'No se encuentra el registro ' . $scannedValue
                ]);
            }
        }

        $checkQry = Scan::query();

        if (session()->has('currentEvent')) {
            $checkQry->where('event_id', session('currentEvent'));
        }

        $alreadyScan = $checkDuplicity
            ? $checkQry->where('value', $scannedValue)->exists()
            : false;

        $confirmDuplicate = $request->boolean('confirm_duplicate');

        if ($alreadyScan && $checkDuplicity && !$confirmDuplicate) {
            $scans = Scan::where('event_id', session('currentEvent'))->count();
            $total = $isStorageType
                ? $scans
                : TableAssignment::where('event_id', session('currentEvent'))->count();
            $userScans = Scan::where('event_id', session('currentEvent'))
                ->where('user_id', $request->user()->id)
                ->count();

            return response()->json([
                'location' => $isStorageType ? '' : $search->table_number,
                'name' => $isStorageType ? $scannedValue : $search->guest_name,
                'exists' => 1,
                'requires_confirmation' => true,
                'message' => 'Desea agregarlo nuevamente?',
                'scans' => $scans,
                'total' => $total,
                'user_scans' => $userScans,
            ]);
        }

        if (! $alreadyScan || ($alreadyScan && $checkDuplicity && $confirmDuplicate)) {
            $scanPayload = [
                'user_id'    => $request->user()->id,
                'event_id'   => session('currentEvent'),
                'value'      => $scannedValue,
                'scanned_at' => now(),
                'origin'     => Scan::ORIGIN_AUTOMATIC,
            ];

            if (! $isStorageType && $search) {
                $scanPayload['id_list'] = Str::limit((string) $search->table_number, 200, '');
                $scanPayload['qr_list'] = Str::limit((string) $search->guest_name, 200, '');
                $listObs = $search->observations;
                $scanPayload['observations'] = filled($listObs) ? trim((string) $listObs) : null;
            }

            Scan::create($scanPayload);
        }

        $scans = Scan::where('event_id', session('currentEvent'))->count();
        $total = $isStorageType
            ? $scans
            : TableAssignment::where('event_id', session('currentEvent'))->count();
        $userScans = Scan::where('event_id', session('currentEvent'))->where('user_id', $request->user()->id)->count();

        return response()->json([
                'location' => $isStorageType ? '' : $search->table_number,
                'name' => $isStorageType ? $scannedValue : $search->guest_name,
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
            'value' => ['nullable', 'string', 'max:255'],
            'observation' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();
        $value = trim((string) ($data['value'] ?? ''));
        $observation = trim((string) ($data['observation'] ?? ''));

        if ($value === '' && $observation === '') {
            $randomSuffix = strtoupper(Str::random(8));
            $value = 'MANUAL-' . $randomSuffix;
            $observation = 'OBS-' . $randomSuffix;
        }

        $event = Event::findOrFail(session('currentEvent'));
        $isStorageType = (int) ($event->scan_type ?? 1) === 2;

        $manualPayload = [
            'user_id' => $request->user()->id,
            'event_id' => session('currentEvent'),
            'value' => $value,
            'scanned_at' => now(),
            'origin' => Scan::ORIGIN_MANUAL,
        ];

        if (! $isStorageType) {
            $assignment = TableAssignment::query()
                ->where('event_id', session('currentEvent'))
                ->where('guest_name', $value)
                ->first();

            if ($assignment) {
                $manualPayload['id_list'] = Str::limit((string) $assignment->table_number, 200, '');
                $manualPayload['qr_list'] = Str::limit((string) $assignment->guest_name, 200, '');
                $listObs = $assignment->observations;
                $manualPayload['observations'] = filled($listObs) ? trim((string) $listObs) : null;
            } else {
                $manualPayload['observations'] = $observation !== '' ? $observation : null;
            }
        } else {
            $manualPayload['observations'] = $observation !== '' ? $observation : null;
        }

        $scan = Scan::create($manualPayload);

        $scans = Scan::where('event_id', session('currentEvent'))->count();
        $total = $isStorageType
            ? $scans
            : TableAssignment::where('event_id', session('currentEvent'))->count();
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
