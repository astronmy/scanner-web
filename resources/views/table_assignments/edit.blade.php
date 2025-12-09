<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Ubicación
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-2xl font-semibold mb-6">
                        Editar Ubicación de Mesa
                    </h1>

                    <form action="{{ route('assignments.update', $assignment) }}"
                          method="POST"
                          class="space-y-4">

                        @csrf
                        @method('PUT')

                        {{-- Nro Mesa --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Nro Mesa
                            </label>
                            <input type="number" name="table_number"
                                   value="{{ old('table_number', $assignment->table_number) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                            @error('table_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Invitado --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Invitado
                            </label>
                            <input type="text" name="guest_name"
                                   value="{{ old('guest_name', $assignment->guest_name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                            @error('guest_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2 pt-4">
                            <a href="{{ route('assignments.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300
                                      text-sm font-medium rounded-md bg-white text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent
                                           text-sm font-medium rounded-md bg-indigo-600 text-white
                                           hover:bg-indigo-700">
                                Guardar Cambios
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
