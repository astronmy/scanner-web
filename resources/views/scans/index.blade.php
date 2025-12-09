<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escaneos realizados
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
                    </div>

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('scans.index') }}" class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                            </div>

                            {{-- Usuario --}}
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
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option value="">Todos</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                            {{ $user->name ?? $user->email }}
                                        </option>
                                    @endforeach
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
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
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
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                       rounded-md bg-emerald-600 text-white hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Filtrar
                            </button>

                            <a href="{{ route('scans.index') }}"
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
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Valor
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Fecha / Hora
                                        </th>
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
                                                {{ $scan->user?->name ?? $scan->user?->email ?? '—' }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                                {{ $scan->scanned_at?->format('d/m/Y H:i') ?? $scan->created_at?->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
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
