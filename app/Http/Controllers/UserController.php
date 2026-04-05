<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Evento activo en sesión (dashboard). Listado y ABM se limitan a ese evento.
     * Admin: basta con que el evento exista (alineado con DashboardController::selectEvent).
     * Resto: debe estar asignado al evento en event_user.
     *
     * @return \Illuminate\Support\Collection<int, int>
     */
    protected function scopedEventIdsForUserManagement(Request $request): \Illuminate\Support\Collection
    {
        if (! session()->has('currentEvent')) {
            return collect();
        }

        $eventId = (int) session('currentEvent');
        $user = $request->user();

        // Misma regla que DashboardController::selectEvent: el admin puede operar en
        // cualquier evento elegido aunque no figure en event_user.
        if ($user->isAdmin()) {
            return Event::query()->whereKey($eventId)->exists()
                ? collect([$eventId])
                : collect();
        }

        $hasAccess = $user
            ->events()
            ->where('events.id', $eventId)
            ->exists();

        return $hasAccess ? collect([$eventId]) : collect();
    }

    public function index(Request $request)
    {
        $query = User::query()->withCount('events');

        $scopedIds = $this->scopedEventIdsForUserManagement($request);

        if ($scopedIds->isEmpty()) {
            $query->whereRaw('1 = 0');
        } else {
            $eventId = (int) $scopedIds->first();
            $query->whereHas('events', function ($q) use ($eventId) {
                $q->where('events.id', $eventId);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query
            ->orderBy('name')
            ->paginate(15);

        $roles = RoleEnum::cases();

        return view('users.index', compact('users', 'roles'));
    }

    public function create(Request $request)
    {
        $scopedIds = $this->scopedEventIdsForUserManagement($request);
        if ($scopedIds->isEmpty()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Seleccioná un evento en el dashboard para gestionar usuarios.');
        }

        $roles  = RoleEnum::cases();
        $events = Event::query()
            ->whereIn('id', $scopedIds)
            ->orderBy('start_date')
            ->get();

        return view('users.create', compact('roles', 'events'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $allowedEventIds = $this->scopedEventIdsForUserManagement($request)->all();
        $eventIds = array_values(array_intersect($data['events'] ?? [], $allowedEventIds));

        if ($eventIds === []) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'events' => 'Debe asignar el usuario al evento actual (elegí un evento en el dashboard).',
                ]);
        }

        // password se hashea solo por el cast "hashed" del modelo
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => $data['password'],
        ]);

        $user->events()->sync($eventIds);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Request $request, User $user)
    {
        $scopedIds = $this->scopedEventIdsForUserManagement($request);
        if ($scopedIds->isEmpty()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Seleccioná un evento en el dashboard para gestionar usuarios.');
        }

        $eventId = (int) $scopedIds->first();
        if (! $user->events()->where('events.id', $eventId)->exists()) {
            abort(403);
        }

        $roles  = RoleEnum::cases();
        $events = Event::query()
            ->whereIn('id', $scopedIds)
            ->orderBy('start_date')
            ->get();
        $userEventIds = $user->events()->pluck('events.id')->toArray();

        return view('users.edit', compact('user', 'roles', 'events', 'userEventIds'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $scopedIds = $this->scopedEventIdsForUserManagement($request);
        if ($scopedIds->isEmpty()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Seleccioná un evento en el dashboard para gestionar usuarios.');
        }

        $eventId = (int) $scopedIds->first();
        if (! $user->events()->where('events.id', $eventId)->exists()) {
            abort(403);
        }

        $data = $request->validated();

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];

        if (!empty($data['password'])) {
            $user->password = $data['password']; // hashed por cast
        }

        $user->save();

        $allowedEventIds = $scopedIds->all();
        $fromForm = array_values(array_intersect($data['events'] ?? [], $allowedEventIds));
        $otherIds = $user->events()->where('events.id', '!=', $eventId)->pluck('events.id')->all();
        $merged = array_values(array_unique(array_merge($otherIds, $fromForm)));

        if ($merged === []) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'events' => 'El usuario debe tener al menos un evento asignado.',
                ]);
        }

        $user->events()->sync($merged);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user)
    {
        $scopedIds = $this->scopedEventIdsForUserManagement($request);
        if ($scopedIds->isEmpty() || ! $user->events()->where('events.id', (int) $scopedIds->first())->exists()) {
            abort(403);
        }

        // opcional: evitar borrarse a sí mismo
        if (auth()->id() === $user->id) {
            return redirect()
                ->back()
                ->with('error', 'No podés eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
