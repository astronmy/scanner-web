<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <form method="GET"
                          action="{{ route('dashboard.event', $event->event_id ?? $event->id) }}"
                          class="w-full">

                        <button type="submit"
                                class="w-full
                                       aspect-[16/9] md:aspect-[16/9]
                                       flex flex-col items-center justify-center gap-2
                                       rounded-2xl
                                       bg-emerald-600 hover:bg-emerald-700
                                       text-white
                                       shadow-xl
                                       transition-all duration-200 active:scale-95
                                       px-4">

                            <span class="text-xl font-bold tracking-wide text-center">
                                {{ $event->name }}
                            </span>

                        </button>
                    </form>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
