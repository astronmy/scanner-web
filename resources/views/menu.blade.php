<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Accesos del evento {{ session('currentEventName') ? ' - '.session('currentEventName') : '' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    Volver a selección de eventos
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('assignments.index') }}"
                   class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow hover:shadow-md transition-shadow">
                    <div class="shrink-0 w-12 h-12 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 4.5h9A1.5 1.5 0 0118 6v12a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 016 18V6a1.5 1.5 0 011.5-1.5zM9 9h6m-6 3h6m-6 3h4.5" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">Listado</span>
                </a>

                <a href="{{ route('scans.index') }}"
                   class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow hover:shadow-md transition-shadow">
                    <div class="shrink-0 w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15.75h4.5v4.5h-4.5v-4.5zM13.5 13.5h1.875v1.875H13.5V13.5zM17.25 13.5h1.875v1.875H17.25V13.5zM13.5 17.25h1.875v1.875H13.5V17.25zM17.25 17.25h1.875v1.875H17.25V17.25z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">Escaneos</span>
                </a>

                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow hover:shadow-md transition-shadow">
                    <div class="shrink-0 w-12 h-12 rounded-lg bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975M15 9.75a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">Usuarios</span>
                </a>

                @if(auth()->user()?->isAdmin() && session('currentEvent'))
                    <a href="{{ route('events.edit', session('currentEvent')) }}"
                       class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow hover:shadow-md transition-shadow">
                        <div class="shrink-0 w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L9.75 16.963 6 18l1.037-3.75 9.825-10.763z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100">Gestionar Evento</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>