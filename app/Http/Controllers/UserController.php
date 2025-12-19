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
    public function index(Request $request)
    {
        $query = User::query()->withCount('events');

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

    public function create()
    {
        $roles  = RoleEnum::cases();
        $events = Event::orderBy('start_date')->get();

        return view('users.create', compact('roles', 'events'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // password se hashea solo por el cast "hashed" del modelo
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => $data['password'],
        ]);

        // asociar eventos
        $user->events()->sync($data['events'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $roles  = RoleEnum::cases();
        $events = Event::orderBy('start_date')->get();
        $userEventIds = $user->events()->pluck('events.id')->toArray();

        return view('users.edit', compact('user', 'roles', 'events', 'userEventIds'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->role  = $data['role'];

        if (!empty($data['password'])) {
            $user->password = $data['password']; // hashed por cast
        }

        $user->save();

        $user->events()->sync($data['events'] ?? []);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
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
