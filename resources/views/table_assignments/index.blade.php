<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Listado  - {{ session('currentEventName') ?? ''}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Título + botón (ya no hace falta título grande acá porque está en el header, pero si querés lo podés dejar) --}}
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Listado 
                        </h1>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-[#406075] text-white hover:bg-[#355566] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075]">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Volver al dashboard
                            </a>
                            <a href="{{ route('assignments.export', request()->query()) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4.5 15.75v1.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-1.5" />
                                </svg>
                                Exportar Excel
                            </a>
                            @unless(auth()->user()?->isUser())
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    data-bulk-delete-btn>
                                    <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15m-1.5 0-1.002 11.018A2.25 2.25 0 0114.757 20.25H9.243a2.25 2.25 0 01-2.241-1.732L6 7.5m3-3h6a1.5 1.5 0 011.5 1.5v1.5h-9V6A1.5 1.5 0 019 4.5z" />
                                    </svg>
                                    Eliminar todo el listado
                                </button>

                                {{-- Botón para importar --}}
                                <a href="{{ route('assignments.import-form') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
          bg-violet-600 text-white hover:bg-violet-700
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                                    <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V9m0 0 4 4m-4-4-4 4M4.5 15.75v1.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-1.5" />
                                    </svg>
                                    Importar desde Excel
                                </a>
                            @endunless
                        </div>
                    </div>

                    {{-- Filtros --}}
                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('assignments.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="table_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    ID
                                </label>
                                <input
                                    type="text"
                                    name="table_number"
                                    id="table_number"
                                    value="{{ request('table_number') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-900
                       text-gray-900 dark:text-gray-100
                       placeholder-gray-400 dark:placeholder-gray-500
                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label for="guest_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    QR
                                </label>
                                <input
                                    type="text"
                                    name="guest_name"
                                    id="guest_name"
                                    value="{{ request('guest_name') }}"
                                    placeholder="Buscar por nombre..."
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-900
                       text-gray-900 dark:text-gray-100
                       placeholder-gray-400 dark:placeholder-gray-500
                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
           rounded-md bg-emerald-600 text-white hover:bg-emerald-700
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h18M6.75 5.25v13.5A1.5 1.5 0 008.25 20.25h7.5a1.5 1.5 0 001.5-1.5V5.25M9.75 10.5h4.5" />
                                </svg>
                                Filtrar
                            </button>

                            <a href="{{ route('assignments.index') }}"
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
                    <div class="mt-6 bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                            ID Registro
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                            ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/2">
                                            QR
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/3">
                                            Observaciones
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40">
                                            Creado
                                        </th>
                                        @unless(auth()->user()?->isUser())
                                            <th scope="col"
                                                class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right w-32">
                                                Acciones
                                            </th>
                                        @endunless
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($tableAssignments as $assignment)
                                    <tr class="hover:bg-gray-50 hover:dark:bg-gray-800">
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $assignment->id }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-700 dark:text-gray-200">
                                            {{ $assignment->table_number }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $assignment->guest_name }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-700 dark:text-gray-200 whitespace-normal break-words">
                                            {{ $assignment->observations ?? '—' }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $assignment->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        @unless(auth()->user()?->isUser())
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('assignments.edit', $assignment) }}"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs
                                      rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                                                    <svg class="mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L9.75 16.963 6 18l1.037-3.75 9.825-10.763z" />
                                                    </svg>
                                                    Editar
                                                </a>
                                                <button type="button"
                                                    class="delete-assignment-btn inline-flex items-center px-2 py-1 border border-transparent text-xs rounded-md bg-red-600 text-white hover:bg-red-700"
                                                    data-delete-url="{{ route('assignments.destroy', $assignment) }}"
                                                    title="Eliminar registro">
                                                    <svg class="mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-1.5 0-.663 10.608A2.25 2.25 0 0113.59 20.25h-3.18a2.25 2.25 0 01-2.247-2.142L7.5 7.5m3-3h3a1.5 1.5 0 011.5 1.5v1.5h-6V6a1.5 1.5 0 011.5-1.5z" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </td>
                                        @endunless
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()?->isUser() ? 5 : 6 }}" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No se encontraron Listado .
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $tableAssignments->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-modal name="confirm-delete-assignment" :show="false" maxWidth="md">
        <div class="p-6 sm:p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Eliminar registro del listado</h3>
            <p class="text-base font-normal text-gray-600 dark:text-gray-400 mb-8">
                ¿Está seguro que desea eliminar este registro? Esta acción no se puede deshacer.
            </p>
            <form id="delete-assignment-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                        x-on:click="$dispatch('close-modal', 'confirm-delete-assignment')">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        document.querySelectorAll('.delete-assignment-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var url = this.getAttribute('data-delete-url');
                if (url) {
                    document.getElementById('delete-assignment-form').action = url;
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-assignment' }));
                }
            });
        });
    </script>

    <x-modal name="confirm-delete-all-assignments" :show="false" maxWidth="md">
        <div class="p-6 sm:p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Eliminar todo el listado</h3>
            <p class="text-base font-normal text-gray-600 dark:text-gray-400 mb-8">
                ¿Está seguro? Se eliminarán todos los registros del listado del evento seleccionado.
            </p>
            <form method="POST" action="{{ route('assignments.destroy-all') }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                        x-on:click="$dispatch('close-modal', 'confirm-delete-all-assignments')">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Eliminar todo
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        document.querySelectorAll('[data-bulk-delete-btn]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-all-assignments' }));
            });
        });
    </script>
</x-app-layout>