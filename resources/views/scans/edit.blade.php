<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar scan manual
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('scans.update', $scan) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Valor
                            </label>
                            <input
                                type="text"
                                name="value"
                                id="value"
                                value="{{ old('value', $scan->value) }}"
                                required
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('value')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Observaciones
                            </label>
                            <textarea
                                name="observations"
                                id="observations"
                                rows="4"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('observations', $scan->observations) }}</textarea>
                            @error('observations')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="scanned_at" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Fecha / Hora
                            </label>
                            <input
                                type="datetime-local"
                                name="scanned_at"
                                id="scanned_at"
                                value="{{ old('scanned_at', optional($scan->scanned_at)->format('Y-m-d\\TH:i')) }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('scanned_at')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <a href="{{ route('scans.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium
                                      rounded-md bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                       rounded-md bg-emerald-600 text-white hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
