<header class="navbar" x-data="{ open: false, atTop: false }"
    @if (request()->is('admin*'))
        :class="{ 'sticky top-0 z-50': atTop }"
        @scroll.window="atTop = (window.pageYOffset < 65) ? false: true"
    @endif
>
    <div class="layout-container flex px-4 sm:px-6 lg:px-8 h-16 justify-between"
        :class="{ 'md:hidden': atTop }"
    >
        <a class="navbar-brand logo" href="{{ url('/') }}">{{ config('app.name') }}</a>

        @auth
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                {{-- Settings Dropdown --}}
                <div class="ml-3 relative">
                    <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                        <div x-on:click="open = ! open">
                            <span class="inline-flex rounded-md">
                                <button class="navbar-toggler items-center">
                                    <div class="text-base font-semibold text-primary-700">
                                        {{ str()->title(auth()->user()->name) }}
                                    </div>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path></svg>
                                </button>
                            </span>
                        </div>
                        <div class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                            x-on:click="open = false" x-show="open"
                            x-transition
                            {{-- Prevent blinking --}}
                            style="display: none;"
                        >
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                @if (Route::currentRouteName() != 'dashboard')
                                    <a class="nav-item" href="{{ route('dashboard') }}">
                                        @svg('icon-dashboard', 'mr-1')
                                        {{ __('Dashboard') }}
                                    </a>

                                    <div class="border-t border-border-200"></div>
                                @endif

                                {{-- Account Management --}}
                                <div class="block px-4 py-2 text-xs text-slate-400">
                                    {{ __('Manage Account') }}
                                </div>

                                @include('partials.header_nav-item_account')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('login') }}" class="btn btn-secondary text-xl font-medium">
                    <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
                    </svg>
                    {{ __('Log in') }}
                </a>
            </div>
        @endauth

        {{-- Mobile hamburger menu button --}}
        <div class="-mr-2 flex items-center sm:hidden">
            <button class="navbar-toggler rounded-md
                    text-slate-400 hover:text-slate-500 focus:text-slate-500
                    hover:bg-slate-100 focus:bg-slate-100 focus:outline-none"
                x-on:click="open = ! open"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Responsive Navigation Menu (Mobile) --}}
    <div class="navbar-mobile sm:hidden block"
        :class="{'block': open, 'hidden': ! open}" x-show="open"
        x-transition
        {{-- Prevent blinking --}}
        style="display: none;"
    >
        @auth
            <div class="pt-2 pb-3 space-y-1">
                <x-nav-item route-name="dashboard">@svg('icon-dashboard', 'mr-1') {{ __('Dashboard') }}</x-nav-item>
                @role('admin')
                    <x-nav-item route-name="dboard.allurl">@svg('icon-link', 'mr-1') {{ __('URL List') }}</x-nav-item>
                    <x-nav-item route-name="user.index">@svg('icon-people', 'mr-1') {{ __('User List') }}</x-nav-item>
                    <x-nav-item route-name="dboard.about">@svg('icon-about-system', 'mr-1') {{ __('About') }}</x-nav-item>
                @endrole
            </div>

            {{-- Responsive Settings Options --}}
            <div class="pt-4 pb-1 border-t border-border-200">
                <div class="flex items-center px-4">
                    <div>
                        <div class="nav-item-username">{{ auth()->user()->name }}</div>
                        <div class="nav-item-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    {{-- Account Management --}}
                    @include('partials.header_nav-item_account')
                </div>
            </div>
        @else
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 font-medium">
                    {{ __('Log in') }}
                </a>
                @if (Route::has('register') and config('urlhub.registration'))
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 font-medium">
                        {{ __('Sign up') }}
                    </a>
                @endif
            </div>
        @endauth
    </div> {{-- End Responsive Navigation Menu --}}

    {{-- It should only appear on the dashboard page only. --}}
    @if (request()->is('admin*'))
        <nav class="layout-container grid grid-cols-2 pt-1 px-4 sm:px-6 lg:px-8 ">
            <div class="hidden sm:flex">
                <x-nav-item_local-menu route-name="dashboard" icon="icon-dashboard">
                    {{ __('Dashboard') }}
                </x-nav-item_local-menu>

                @role('admin')
                    <x-nav-item_local-menu route-name="dboard.allurl" icon="icon-link">
                        {{ __('URL List') }}
                    </x-nav-item_local-menu>

                    <x-nav-item_local-menu route-name="user.index" icon="icon-people">
                        {{ __('User List') }}
                    </x-nav-item_local-menu>

                    <x-nav-item_local-menu route-name="dboard.about" icon="icon-about-system">
                        {{ __('About') }}
                    </x-nav-item_local-menu>
                @endrole
            </div>
        </nav>
    @endif
</header>
