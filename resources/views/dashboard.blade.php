<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Seleccionar evento
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <form method="GET"
                          action="{{ route('dashboard.event', $event->event_id ?? $event->id) }}"
                          class="w-full h-full">

                        <button type="submit"
                                class="w-full h-full min-h-[200px]
                                       flex flex-col
                                       rounded-2xl
                                       bg-white dark:bg-gray-800
                                       border border-gray-200 dark:border-gray-700
                                       shadow-lg hover:shadow-xl
                                       overflow-hidden
                                       transition-all duration-200 active:scale-[0.98]
                                       text-left">
                            <div class="aspect-[16/10] w-full shrink-0 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <div class="w-full h-full flex items-center justify-center bg-emerald-600/20 dark:bg-emerald-500/20">
                                    <span class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 opacity-60">E</span>
                                </div>
                            </div>

                            <div class="flex-1 flex items-center justify-center p-4">
                                <span class="text-lg font-bold text-gray-800 dark:text-gray-100 text-center leading-tight">
                                    {{ $event->name }}
                                </span>
                            </div>

                        </button>
                    </form>
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
