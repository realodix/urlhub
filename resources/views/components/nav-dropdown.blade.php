<div {{ $attributes->merge(['class' => 'hidden sm:flex sm:items-center sm:ml-6']) }}>
@auth
    {{-- Settings Dropdown --}}
    <div class="ml-3 relative">
        <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
            <div x-on:click="open = ! open">
                <button class="navbar-toggler items-center">
                    <div class="text-base font-semibold text-primary-700 dark:text-primary-500">
                        {{ str()->title(auth()->user()->name) }}
                    </div>
                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path></svg>
                </button>
            </div>
            <div class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                x-on:click="open = false" x-show="open"
                x-transition
                {{-- Prevent blinking --}}
                style="display: none;"
            >
                <div class="bg-white dark:bg-dark-900 ring-1 ring-black/5 dark:ring-dark-700 rounded-md py-1">
                    @if (Route::currentRouteName() != 'dashboard')
                        <a class="nav-item" href="{{ route('dashboard') }}">
                            @svg('icon-dashboard', 'mr-1')
                            {{ __('Dashboard') }}
                        </a>

                        <hr>
                    @endif

                    {{-- Account Management --}}
                    <div class="block px-4 py-2 text-xs text-slate-400 dark:text-dark-500">
                        {{ __('Manage Account') }}
                    </div>

                    @include('partials.header_nav-item_account')
                </div>
            </div>
        </div>
    </div>
@else
    <a href="{{ route('login') }}"
        class="btn text-xl font-medium hover:bg-gray-100
            dark:!bg-dark-900 dark:hover:!bg-dark-900/40 dark:!text-primary-500 dark:hover:!border-primary-500"
    >
        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path></svg>
        {{ __('Log in') }}
    </a>
@endauth
</div>
