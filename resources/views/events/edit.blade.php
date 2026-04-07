<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar evento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                        Editar evento
                    </h1>

                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Denominación --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Denominación
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $event->name) }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900
                                       text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Imagen (dashboard)
                            </label>
                            @if($event->cover_image)
                                <div class="mb-2 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 max-w-xs">
                                    <img src="{{ $event->coverImageUrl() }}" alt="" class="w-full h-28 object-cover">
                                </div>
                            @endif
                            <input
                                type="file"
                                name="cover_image"
                                id="cover_image"
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-200
                                       file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                       file:text-sm file:font-medium file:bg-gray-100 file:text-gray-800
                                       dark:file:bg-gray-700 dark:file:text-gray-100">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Opcional. Dejar vac&iacute;o para conservar la imagen actual. M&aacute;x. 4&nbsp;MB.</p>
                            @error('cover_image')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Label --}}
                        <div>
                            <label for="label" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Etiqueta
                            </label>
                            <input
                                type="text"
                                name="label"
                                id="label"
                                value="{{ old('label', $event->label) }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900
                                       text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('label')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Desde --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Fecha desde
                            </label>
                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900
                                       text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('start_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hasta --}}
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Fecha hasta
                            </label>
                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-900
                                       text-gray-900 dark:text-gray-100
                                       shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('end_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">Preferencias Escaner</h3>
                            <div class="mb-3">
                                <label for="message_not_found" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Mensaje para "ya ingresó previamente"
                                </label>
                                <input
                                    type="text"
                                    name="message_not_found"
                                    id="message_not_found"
                                    value="{{ old('message_not_found', $event->message_not_found ?? 'La persona ya ingresó previamente') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('message_not_found')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="scan_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Tipo de escaneo
                                </label>
                                <select
                                    name="scan_type"
                                    id="scan_type"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="1" @selected((string) old('scan_type', (string) ($event->scan_type ?? 1)) === '1')>CHECK IN</option>
                                    <option value="2" @selected((string) old('scan_type', (string) ($event->scan_type ?? 1)) === '2')>STORAGE</option>
                                </select>
                                @error('scan_type')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="separator" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                    Separador
                                </label>
                                <input
                                    type="text"
                                    name="separator"
                                    id="separator"
                                    value="{{ old('separator', $event->separator) }}"
                                    placeholder="Ejemplo: -"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900
                                           text-gray-900 dark:text-gray-100
                                           shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('separator')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Mostrar botón "Nuevo" en Escáner</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Si está desactivado, no se mostrará la opción "Nuevo".</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="new_button_enabled" value="0">
                                    <input type="checkbox"
                                           name="new_button_enabled"
                                           value="1"
                                           class="sr-only peer"
                                           @checked(old('new_button_enabled', (int) $event->new_button_enabled))
                                    >
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 dark:peer-focus:ring-indigo-400 rounded-full peer dark:bg-gray-600 peer-checked:bg-emerald-600 transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 h-5 w-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                            @error('new_button_enabled')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Iniciar automáticamente</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Luego de cada escaneo, reinicia como si tocaras "Nuevo".</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="autostart" value="0">
                                    <input type="checkbox"
                                           name="autostart"
                                           value="1"
                                           class="sr-only peer"
                                           @checked(old('autostart', (int) ($event->autostart ?? 0)))
                                    >
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 dark:peer-focus:ring-indigo-400 rounded-full peer dark:bg-gray-600 peer-checked:bg-emerald-600 transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 h-5 w-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                            @error('autostart')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Chequear duplicidad</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Si detecta duplicado, pedirá confirmación para volver a guardar.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="check_duplicity" value="0">
                                    <input type="checkbox"
                                           name="check_duplicity"
                                           value="1"
                                           class="sr-only peer"
                                           @checked(old('check_duplicity', (int) ($event->check_duplicity ?? 0)))
                                    >
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 dark:peer-focus:ring-indigo-400 rounded-full peer dark:bg-gray-600 peer-checked:bg-emerald-600 transition-colors"></div>
                                    <div class="absolute left-0.5 top-0.5 h-5 w-5 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>
                            @error('check_duplicity')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <a href="{{ route('events.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium
                                      rounded-md bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200
                                      hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Cancelar
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                       rounded-md bg-emerald-600 text-white hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L9.75 16.963 6 18l1.037-3.75 9.825-10.763z" />
                                </svg>
                                Actualizar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
