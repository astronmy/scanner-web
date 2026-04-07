<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escaneos - {{ session('currentEventName') ?? ''}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Título --}}
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Historial de Scans
                        </h1>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-[#406075] text-white hover:bg-[#355566] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075]">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Volver al dashboard
                            </a>
                            <a href="{{ route('scans.export', request()->query()) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
                  bg-violet-600 text-white hover:bg-violet-700
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4.5 15.75v1.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-1.5" />
                                </svg>
                                Exportar
                            </a>
                        </div>
                    </div>


                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('scans.index') }}" class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4 {{ auth()->user()->isAdmin() ? 'md:grid-cols-5' : 'md:grid-cols-4' }}">
                            {{-- Valor escaneado --}}
                            <div class="md:col-span-2">
                                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Valor escaneado
                                </label>
                                <input
                                    type="text"
                                    name="value"
                                    id="value"
                                    value="{{ request('value') }}"
                                    placeholder="Buscar por valor..."
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            @if(auth()->user()->isAdmin())
                                {{-- Usuario (solo admin) --}}
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                        Usuario
                                    </label>
                                    <select
                                        name="user_id"
                                        id="user_id"
                                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                               bg-white dark:bg-gray-900
                                               text-gray-900 dark:text-gray-100
                                               shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="">Todos</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(request('user_id')==$user->id)>
                                            {{ $user->name ?? $user->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div>
                                <label for="origin" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Tipo de carga
                                </label>
                                <select
                                    name="origin"
                                    id="origin"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Todos</option>
                                    <option value="{{ \App\Models\Scan::ORIGIN_MANUAL }}" @selected(request('origin') === \App\Models\Scan::ORIGIN_MANUAL)>Manual</option>
                                    <option value="{{ \App\Models\Scan::ORIGIN_AUTOMATIC }}" @selected(request('origin') === \App\Models\Scan::ORIGIN_AUTOMATIC)>Automático</option>
                                </select>
                            </div>

                            {{-- Fecha desde / hasta --}}
                            <div class="md:col-span-1">
                                <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Desde
                                </label>
                                <input
                                    type="date"
                                    name="from"
                                    id="from"
                                    value="{{ request('from') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="md:col-span-1">
                                <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Hasta
                                </label>
                                <input
                                    type="date"
                                    name="to"
                                    id="to"
                                    value="{{ request('to') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                       rounded-md bg-emerald-600 text-white hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h18M6.75 5.25v13.5A1.5 1.5 0 008.25 20.25h7.5a1.5 1.5 0 001.5-1.5V5.25M9.75 10.5h4.5" />
                                </svg>
                                Filtrar
                            </button>

                            <a href="{{ route('scans.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                      rounded-md bg-red-600 text-white hover:bg-red-700
                                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
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
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Valor
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Observaciones
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Fecha / Hora
                                        </th>
                                        @unless(auth()->user()?->isUser())
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right w-24">
                                                Acciones
                                            </th>
                                        @endunless
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($scans as $scan)
                                    <tr class="hover:bg-gray-50 hover:dark:bg-gray-800">
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $scan->id }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $scan->value }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $scan->observations ?? $scan->assignment_observations ?? '—' }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $scan->user?->name ?? $scan->user?->email ?? '—' }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $scan->scanned_at?->format('d/m/Y H:i') ?? $scan->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        @unless(auth()->user()?->isUser())
                                            <td class="px-6 py-3 whitespace-nowrap text-right">
                                                @if($scan->origin === \App\Models\Scan::ORIGIN_MANUAL || ($isStorageType ?? false))
                                                    <a href="{{ route('scans.edit', $scan) }}"
                                                       class="inline-flex items-center px-3 py-1 mr-1 text-xs font-semibold rounded-md
                                                              bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                                                        <svg class="mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L9.75 16.963 6 18l1.037-3.75 9.825-10.763z" />
                                                        </svg>
                                                        Editar
                                                    </a>
                                                @endif

                                                <form action="{{ route('scans.destroy', $scan) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Seguro que querés eliminar este scan?');"
                                                    class="inline-block">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                   bg-red-600 text-white hover:bg-red-700
                   focus:outline-none focus:ring-2 focus:ring-offset-2
                   focus:ring-red-500">
                                                        <svg class="mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-1.5 0-.663 10.608A2.25 2.25 0 0113.59 20.25h-3.18a2.25 2.25 0 01-2.247-2.142L7.5 7.5m3-3h3a1.5 1.5 0 011.5 1.5v1.5h-6V6a1.5 1.5 0 011.5-1.5z" />
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        @endunless
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()?->isUser() ? 5 : 6 }}" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No se encontraron registros de scans.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $scans->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>