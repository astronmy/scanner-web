<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $query = Event::query();
        } else {
            $query = $user->events()->getQuery();
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->to);
        }

        $events = $query
            ->orderBy('start_date')
            ->paginate(15);

        return view('dashboard', compact('events'));
    }

    public function selectEvent(Request $request, int $eventId) {

        $event = Event::findOrFail($eventId);
        $user = $request->user();

        if(! $user->hasEvent($eventId) && !$user->isAdmin()) {
            abort(404);
        }

        $request->session()->put('currentEvent', $event->id); 
        $request->session()->put('currentEventName', $event->name); 

        return view('menu');
    }

    public function selectEventAndScan(Request $request, int $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = $request->user();

        if (! $user->hasEvent($eventId) && ! $user->isAdmin()) {
            abort(404);
        }

        $request->session()->put('currentEvent', $event->id);
        $request->session()->put('currentEventName', $event->name);

        if ((int) ($event->scan_type ?? 1) === 3) {
            return redirect()
                ->route('dashboard')
                ->with('info', 'Este evento usa el modo LIST: abrí el listado desde el ícono de documento en el dashboard.');
        }

        return redirect()->route('scanners.start');
    }

    public function setEventContext(Request $request, int $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = $request->user();

        if (! $user->hasEvent($eventId) && ! $user->isAdmin()) {
            abort(404);
        }

        $request->session()->put('currentEvent', $event->id);
        $request->session()->put('currentEventName', $event->name);

        $isStorageType = (int) ($event->scan_type ?? 1) === 2;

        return response()->json([
            'is_storage_type' => $isStorageType,
        ]);
    }
}
