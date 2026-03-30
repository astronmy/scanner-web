@php
    $currentEventId = session('currentEvent');
    $eventTitle = collect($navEvents ?? [])->firstWhere('id', $currentEventId)?->name ?? 'Seleccionar evento';
@endphp
<nav x-data="{ open: false, eventDropdownOpen: false }" class="bg-[#406075] dark:bg-gray-800 border-b border-white/15 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center flex-1 min-w-0">
                <div class="relative shrink-0" @click.outside="eventDropdownOpen = false">
                    <button type="button"
                            @click="eventDropdownOpen = ! eventDropdownOpen; open = false"
                            :aria-expanded="eventDropdownOpen"
                            class="inline-flex items-center max-w-[280px] sm:max-w-md px-3 py-2 rounded-md text-left text-sm font-medium text-white/95 dark:text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30 transition">
                        <span class="truncate">{{ $eventTitle }}</span>
                        <svg class="ml-2 shrink-0 h-4 w-4 text-white/80 transition-transform"
                             :class="{ 'rotate-180': eventDropdownOpen }"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="eventDropdownOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 top-full mt-2 z-50 min-w-[360px] sm:min-w-[480px] w-full max-w-[90vw] rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700 overflow-hidden"
                         style="display: none;">
                        <div class="px-3 pt-3 pb-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Eventos</p>
                        </div>
                        <div class="max-h-[70vh] overflow-y-auto p-3 pt-0">
                            @if(collect($navEvents)->isEmpty())
                                <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">No tenés eventos asignados.</p>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($navEvents as $ev)
                                        @php $isCurrent = (int) $ev->id === (int) $currentEventId; @endphp
                                        <form method="GET" action="{{ route('dashboard.event', $ev->id) }}" class="contents">
                                            <button type="submit"
                                                    @click="eventDropdownOpen = false"
                                                    class="relative flex flex-col rounded-xl border-2 overflow-hidden text-left transition focus:outline-none focus:ring-2 focus:ring-[#406075] focus:ring-offset-1 shadow-sm
                                                        {{ $isCurrent
                                                            ? 'border-[#406075] bg-[#406075]/5 dark:bg-[#406075]/20 ring-2 ring-[#406075]/30'
                                                            : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-600/50 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                                                @if($isCurrent)
                                                    <span class="absolute top-2 right-2 z-10 inline-flex items-center rounded-full bg-[#406075] px-2 py-0.5 text-xs font-medium text-white">Actual</span>
                                                @endif
                                                <div class="aspect-[4/3] w-full min-h-[80px] bg-gray-100 dark:bg-gray-500 shrink-0 overflow-hidden">
                                                    <div class="w-full h-full flex items-center justify-center bg-emerald-600/10 dark:bg-emerald-500/10">
                                                        <span class="text-3xl font-bold text-emerald-600/50 dark:text-emerald-400/50">E</span>
                                                    </div>
                                                </div>
                                                <div class="p-2.5 min-h-[3.25rem] flex flex-col justify-center">
                                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200 line-clamp-2">{{ $ev->name }}</span>
                                                    @if($ev->start_date)
                                                        <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $ev->start_date->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if(auth()->user()?->isAdmin())
                            <div class="border-t border-gray-200 dark:border-gray-600 p-2 bg-gray-50 dark:bg-gray-700/80 space-y-1">
                                <a href="{{ route('events.index') }}"
                                   @click="eventDropdownOpen = false"
                                   class="block text-center text-sm font-medium text-[#406075] dark:text-indigo-300 hover:underline">
                                    Gestionar eventos
                                </a>
                                @if($currentEventId)
                                    <a href="{{ route('events.edit', $currentEventId) }}"
                                       @click="eventDropdownOpen = false"
                                       class="block text-center text-sm font-medium text-gray-600 dark:text-gray-300 hover:underline">
                                        Editar evento seleccionado
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white/90 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-white dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white/90 dark:text-gray-500 hover:text-white dark:hover:text-gray-400 hover:bg-white/10 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#406075] dark:bg-gray-800 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium text-white/90 hover:text-white hover:bg-white/10">Dashboard</a>
            @if (session()->has('currentEvent'))
                @unless(auth()->user()?->isUser())
                    <a href="{{ route('assignments.index') }}" class="block px-4 py-2 text-base font-medium text-white/90 hover:text-white hover:bg-white/10">Listado</a>
                @endunless
                <a href="{{ route('scans.index') }}" class="block px-4 py-2 text-base font-medium text-white/90 hover:text-white hover:bg-white/10">Escaneos</a>
                @unless(auth()->user()?->isUser())
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 text-base font-medium text-white/90 hover:text-white hover:bg-white/10">Usuarios</a>
                @endunless
            @endif
        </div>
        <div class="pt-4 pb-3 border-t border-white/15">
            <div class="px-4">
                <div class="font-medium text-base text-white dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm text-white/80 dark:text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :inverted="true">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" :inverted="true" onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
