<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ubicaciones de Mesa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Título + botón (ya no hace falta título grande acá porque está en el header, pero si querés lo podés dejar) --}}
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Ubicaciones de Mesa
                        </h1>

                        {{-- Botón para importar --}}
                        <a href="{{ route('assignments.import-form') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
          bg-violet-600 text-white hover:bg-violet-700
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                            Importar desde Excel
                        </a>
                    </div>

                    {{-- Filtros --}}
                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('assignments.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="table_number" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Nro Mesa
                                </label>
                                <input
                                    type="number"
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
                                    Invitado
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
                                Filtrar
                            </button>

                            <a href="{{ route('assignments.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
          rounded-md bg-red-600 text-white hover:bg-red-700
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                                            ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                            Nro Mesa
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/2">
                                            Invitado
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-40">
                                            Creado
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right w-32">
                                            Acciones
                                        </th>
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
                                        <td class="px-6 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $assignment->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('assignments.edit', $assignment) }}"
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs
                                      rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No se encontraron ubicaciones de mesa.
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
</x-app-layout>