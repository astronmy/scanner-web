<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Scan;
use App\Models\TableAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function searchAssignments(Request $request)
    {
        if (! session()->has('currentEvent')) {
            return response()->json(['data' => []]);
        }

        $event = Event::find(session('currentEvent'));
        if (! $event || (int) ($event->scan_type ?? 1) === 2) {
            return response()->json(['data' => []]);
        }

        $q = trim((string) $request->query('q', ''));

        $query = TableAssignment::query()
            ->where('event_id', session('currentEvent'))
            ->select(['id', 'guest_name', 'table_number', 'observations'])
            ->orderBy('table_number')
            ->orderBy('guest_name')
            ->limit(50);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('guest_name', 'like', '%' . $q . '%')
                    ->orWhere('table_number', 'like', '%' . $q . '%');
            });
        }

        $data = $query->get()->map(static function (TableAssignment $a) {
            return [
                'id' => $a->id,
                'guest_name' => $a->guest_name,
                'table_number' => $a->table_number,
                'observations' => $a->observations,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function storeManual(Request $request)
    {
        if (!session()->has('currentEvent')) {
            return response()->json([
                'message' => 'Seleccioná un evento antes de cargar manualmente.'
            ], 422);
        }

        $eventId = (int) session('currentEvent');

        $validator = Validator::make($request->all(), [
            'value' => ['nullable', 'string', 'max:255'],
            'observation' => ['nullable', 'string', 'max:500'],
            'table_assignment_id' => [
                'nullable',
                'integer',
                Rule::exists('table_assignments', 'id')->where('event_id', $eventId),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();
        $event = Event::findOrFail($eventId);
        $isStorageType = (int) ($event->scan_type ?? 1) === 2;

        $tableAssignmentId = isset($data['table_assignment_id']) ? (int) $data['table_assignment_id'] : null;
        $picked = null;
        if ($tableAssignmentId && ! $isStorageType) {
            $picked = TableAssignment::query()
                ->where('id', $tableAssignmentId)
                ->where('event_id', $eventId)
                ->first();
        }

        if ($picked) {
            $value = trim((string) $picked->guest_name);
            $listObs = $picked->observations;
            $manualPayload = [
                'user_id' => $request->user()->id,
                'event_id' => $eventId,
                'value' => $value,
                'id_list' => Str::limit((string) $picked->table_number, 200, ''),
                'qr_list' => Str::limit((string) $picked->guest_name, 200, ''),
                'observations' => filled($listObs) ? trim((string) $listObs) : null,
                'scanned_at' => now(),
                'origin' => Scan::ORIGIN_MANUAL,
            ];
            $scan = Scan::create($manualPayload);
        } else {
            $value = trim((string) ($data['value'] ?? ''));
            $observation = trim((string) ($data['observation'] ?? ''));

            if ($value === '' && $observation === '') {
                $randomSuffix = strtoupper(Str::random(8));
                $value = 'MANUAL-' . $randomSuffix;
                $observation = 'OBS-' . $randomSuffix;
            }

            $manualPayload = [
                'user_id' => $request->user()->id,
                'event_id' => $eventId,
                'value' => $value,
                'scanned_at' => now(),
                'origin' => Scan::ORIGIN_MANUAL,
            ];

            if (! $isStorageType) {
                $assignment = TableAssignment::query()
                    ->where('event_id', $eventId)
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
        }

        $scans = Scan::where('event_id', $eventId)->count();
        $total = $isStorageType
            ? $scans
            : TableAssignment::where('event_id', $eventId)->count();
        $userScans = Scan::where('event_id', $eventId)
            ->where('user_id', $request->user()->id)
            ->count();

        $locationLabel = $scan->observations
            ? (strlen($scan->observations) > 120 ? Str::limit($scan->observations, 120, '…') : $scan->observations)
            : '—';

        return response()->json([
            'message' => 'Scan manual cargado correctamente.',
            'name' => $scan->value,
            'location' => $locationLabel,
            'exists' => 0,
            'scans' => $scans,
            'total' => $total,
            'user_scans' => $userScans,
            'scan_id' => $scan->id,
            'origin' => $scan->origin,
        ]);
    }
}
