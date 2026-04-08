<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Seleccionar evento
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('info'))
                <div class="mb-4 rounded-lg border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-900 dark:border-sky-800 dark:bg-sky-900/30 dark:text-sky-100" role="alert">
                    {{ session('info') }}
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <div class="w-full h-full min-h-[200px] flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl overflow-hidden transition-all duration-200">
                        <div class="aspect-[16/10] w-full shrink-0 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if($event->cover_image)
                                <img src="{{ $event->coverImageUrl() }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-emerald-600/20 dark:bg-emerald-500/20">
                                    <span class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 opacity-60">E</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center p-4">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-100 text-center leading-tight">
                                {{ $event->name }}
                            </span>

                            @php $evScanType = (int) ($event->scan_type ?? 1); @endphp
                            <div class="mt-4 flex items-center gap-2">
                                @if ($evScanType !== 3)
                                <a href="{{ route('dashboard.event.scanner', $event->event_id ?? $event->id) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                   title="Iniciar escáner">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15.75h4.5v4.5h-4.5v-4.5zM13.5 13.5h1.875v1.875H13.5V13.5zM17.25 13.5h1.875v1.875H17.25V13.5zM13.5 17.25h1.875v1.875H13.5V17.25zM17.25 17.25h1.875v1.875H17.25V17.25z" />
                                    </svg>
                                </a>
                                @else
                                <a href="{{ route('scanners.list', $event->event_id ?? $event->id) }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                   title="Abrir listado (nueva ventana)">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ route('dashboard.event', $event->event_id ?? $event->id) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-[#406075] text-white text-sm font-semibold hover:bg-[#355566] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075]"
                                   title="Opciones del evento">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0l.17.696a1.724 1.724 0 002.573 1.066l.61-.356a1.724 1.724 0 012.35.63 1.724 1.724 0 010 1.724l-.355.61a1.724 1.724 0 001.065 2.573l.697.17a1.724 1.724 0 010 3.35l-.697.17a1.724 1.724 0 00-1.065 2.573l.356.61a1.724 1.724 0 01-.63 2.35 1.724 1.724 0 01-1.724 0l-.61-.356a1.724 1.724 0 00-2.573 1.065l-.17.697a1.724 1.724 0 01-3.35 0l-.17-.697a1.724 1.724 0 00-2.573-1.065l-.61.356a1.724 1.724 0 01-2.35-.63 1.724 1.724 0 010-1.724l.355-.61A1.724 1.724 0 014.317 13.7l-.697-.17a1.724 1.724 0 010-3.35l.697-.17A1.724 1.724 0 005.382 7.44l-.356-.61a1.724 1.724 0 01.63-2.35 1.724 1.724 0 011.724 0l.61.356a1.724 1.724 0 002.573-1.065l.17-.697z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            @if($events->hasPages())
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
