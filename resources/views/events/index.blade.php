<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Eventos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

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

                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Lista de eventos
                        </h1>

                        <a href="{{ route('events.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
                                  bg-emerald-600 text-white hover:bg-emerald-700
                                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Nuevo evento
                        </a>
                    </div>

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('events.index') }}" class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            {{-- Denominación --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Denominación
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ request('name') }}"
                                    placeholder="Buscar por nombre..."
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div>
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

                            <div>
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
                                Filtrar
                            </button>

                            <a href="{{ route('events.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                      rounded-md bg-red-600 text-white hover:bg-red-700
                                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <div class="mt-4 bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Denominación
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40">
                                            Desde
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40">
                                            Hasta
                                        </th>
                                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right w-32">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($events as $event)
                                    <tr class="hover:bg-gray-50 hover:dark:bg-gray-800">
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $event->id }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $event->name }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200 text-xs">
                                            {{ $event->start_date?->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200 text-xs">
                                            {{ $event->end_date?->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('events.edit', $event) }}"
                                                class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                                                          bg-sky-600 text-white hover:bg-sky-700
                                                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                                Editar
                                            </a>

                                            <form action="{{ route('events.destroy', $event) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Seguro que querés eliminar este evento?');">
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
                                        <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No se encontraron eventos.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{ $events->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>