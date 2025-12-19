<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Flash messages --}}
                    @if (session('success'))
                        <div class="mb-4 flex items-start gap-3 rounded-lg border
                                    border-emerald-200 bg-emerald-50 text-emerald-800
                                    dark:border-emerald-800/70 dark:bg-emerald-900/40 dark:text-emerald-100
                                    px-4 py-3 text-sm shadow-sm">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full
                                         bg-emerald-600 text-white text-xs">
                                ✓
                            </span>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 flex items-start gap-3 rounded-lg border
                                    border-red-200 bg-red-50 text-red-800
                                    dark:border-red-800/70 dark:bg-red-900/40 dark:text-red-100
                                    px-4 py-3 text-sm shadow-sm">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full
                                         bg-red-600 text-white text-xs">
                                !
                            </span>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Título + botón crear --}}
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Gestión de usuarios
                        </h1>

                        <a href="{{ route('users.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
                                  bg-emerald-600 text-white hover:bg-emerald-700
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Nuevo usuario
                        </a>
                    </div>

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('users.index') }}" class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Buscar --}}
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Buscar (nombre o email)
                                </label>
                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Ej: Juan, juan@mail.com"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            {{-- Rol --}}
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Rol
                                </label>
                                <select
                                    name="role"
                                    id="role"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Todos</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->value }}" @selected(request('role') === $role->value)>
                                            {{ ucfirst($role->value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                       rounded-md bg-emerald-600 text-white hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Filtrar
                            </button>

                            <a href="{{ route('users.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                      rounded-md bg-red-600 text-white hover:bg-red-700
                                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    {{-- Grilla --}}
                    <div class="mt-4 bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Rol
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Eventos
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right w-40">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50 hover:dark:bg-gray-800">
                                            <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                                {{ $user->id }}
                                            </td>
                                            <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                                {{ $user->name }}
                                            </td>
                                            <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                @php
                                                    $roleColor = match($user->role?->value ?? $user->role) {
                                                        'admin'   => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-100',
                                                        'manager' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-100',
                                                        'user'    => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-100',
                                                        default   => 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-100',
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $roleColor }}">
                                                    {{ ucfirst($user->role?->value ?? $user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200 text-center">
                                                {{ $user->events_count }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('users.edit', $user) }}"
                                                   class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                                                          bg-sky-600 text-white hover:bg-sky-700
                                                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                                    Editar
                                                </a>

                                                <form action="{{ route('users.destroy', $user) }}"
                                                      method="POST"
                                                      class="inline-block"
                                                      onsubmit="return confirm('¿Seguro que querés eliminar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 text-xs font-semibold rounded-md
                                                               bg-red-600 text-white hover:bg-red-700
                                                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                No se encontraron usuarios.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $users->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
