<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-center min-h-[60vh]">

            <a href="{{ route('scanners.start') }}"
                class="w-full max-w-xs aspect-square
              flex flex-col items-center justify-center gap-4
              rounded-2xl
              bg-emerald-600 hover:bg-emerald-700
              text-white
              shadow-xl
              transition-all duration-200 active:scale-95">

                {{-- Ícono QR --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-16 h-16"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15.75h4.5v4.5h-4.5v-4.5zM13.5 13.5h1.875v1.875H13.5V13.5zM17.25 13.5h1.875v1.875H17.25V13.5zM13.5 17.25h1.875v1.875H13.5V17.25zM17.25 17.25h1.875v1.875H17.25V17.25z" />
                </svg>

                {{-- Texto --}}
                <span class="text-xl font-bold tracking-wide">
                    Escanear
                </span>
            </a>

        </div>

    </div>
</x-app-layout>