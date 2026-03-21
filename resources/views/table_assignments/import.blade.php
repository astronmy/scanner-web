<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Importar Listado
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-2xl font-semibold mb-6">
                        Importar Listado
                    </h1>

                    <form action="{{ route('assignments.import') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-4">

                        @csrf

                        {{-- Archivo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Archivo Excel
                            </label>
                            <input type="file" name="file"
                                   class="block w-full text-sm text-gray-700
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                            @error('file')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2 pt-4">
                            <a href="{{ route('assignments.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300
                                      text-sm font-medium rounded-md bg-white text-gray-700 hover:bg-gray-50">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent
                                           text-sm font-medium rounded-md bg-indigo-600 text-white
                                           hover:bg-indigo-700">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V9m0 0 4 4m-4-4-4 4M4.5 15.75v1.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25v-1.5" />
                                </svg>
                                Importar
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
