<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @foreach($events as $event)
        <div class="p-3 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-[60vh]">
            <form method="GET" action="{{ route('dashboard.event', $event->id) }}"
                class="w-full max-w-xs aspect-square">
                <button type="submit"
                    class="w-full h-full
            flex flex-col items-center justify-center gap-4
            rounded-2xl
            bg-emerald-600 hover:bg-emerald-700
            text-white
            shadow-xl
            transition-all duration-200 active:scale-95">

                    <span class="text-xl font-bold tracking-wide">
                        {{ $event->name }}
                    </span>

                </button>
            </form>
        </div>

        @endforeach

    </div>
</x-app-layout>