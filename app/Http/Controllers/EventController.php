<?php

namespace App\Http\Controllers;

use App\Exports\EventScansByEventExport;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
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

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function exportScansByEvent(Request $request)
    {
        $user = $request->user();
        $events = $user->isAdmin()
            ? Event::query()->orderBy('start_date')->get()
            : $user->events()->orderBy('start_date')->get();

        if ($events->isEmpty()) {
            return redirect()
                ->route('events.index')
                ->with('error', 'No hay eventos para exportar.');
        }

        $fileName = 'scans_por_evento_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new EventScansByEventExport($events), $fileName);
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            unset($data['cover_image']);
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();
        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            unset($data['cover_image']);
        }
        if ($request->hasFile('cover_image')) {
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Evento eliminado correctamente.');
    }
}
