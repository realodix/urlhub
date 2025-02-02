<header class="navbar" x-data="{ open: false, atTop: false }"
    @if (request()->is('admin*'))
        :class="{ 'sticky top-0 z-50': atTop }"
        @scroll.window="atTop = (window.pageYOffset < 65) ? false: true"
    @endif
>
    <div class="container-alt px-4 flex h-16 justify-between" :class="{ 'sm:hidden': atTop }">
        <a class="navbar-brand logo" href="{{ url('/') }}">{{ config('app.name') }}</a>

        <div class="flex items-center">
            <x-theme-toggle />

            <x-nav-dropdown/>

            {{-- Mobile hamburger menu button --}}
            <div class="-mr-2 ml-6 flex items-center sm:hidden">
                <button class="navbar-toggler rounded-md
                        text-slate-400 hover:text-slate-500 focus:text-slate-500 hover:bg-gray-950/5 focus:bg-gray-950/5 focus:outline-none
                        dark:text-dark-400 dark:hover:text-dark-400 dark:focus:text-dark-400 dark:bg-dark-800 dark:hover:bg-dark-700 dark:focus:bg-dark-700"
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
    </div>

    {{-- Responsive Navigation Menu (Mobile) --}}
    <div class="navbar-mobile sm:hidden block"
        :class="{'block': open, 'hidden': ! open}" x-show="open"
        x-transition
        {{-- Prevent blinking --}}
        style="display: none;"
    >
        @auth
            @role('admin')
                <div class="pt-2 pb-3 space-y-1 border-b border-border-200">
                    <x-nav-item route-name="dashboard">@svg('icon-dashboard', 'mr-1') {{ __('Dashboard') }}</x-nav-item>
                    <x-nav-item route-name="dboard.allurl">@svg('icon-link', 'mr-1') {{ __('URL List') }}</x-nav-item>
                    <x-nav-item route-name="user.index">@svg('icon-people', 'mr-1') {{ __('User List') }}</x-nav-item>
                    <x-nav-item route-name="dboard.settings">@svg('icon-setting', 'mr-1') {{ __('Settings') }}</x-nav-item>
                    <x-nav-item route-name="dboard.about">@svg('icon-about-system', 'mr-1') {{ __('About') }}</x-nav-item>
                </div>
            @endrole

            {{-- Responsive Settings Options --}}
            <div class="pt-4 pb-1">
                <div class="flex items-center px-4">
                    <div>
                        <div class="font-semibold">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-slate-500 dark:text-blue-500 text-sm">{{ auth()->user()->email }}</div>
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
                @if (Route::has('register') && settings()->anyone_can_register)
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 font-medium">
                        {{ __('Sign up') }}
                    </a>
                @endif
            </div>
        @endauth
    </div> {{-- End Responsive Navigation Menu --}}

    {{-- It should only appear on the dashboard page only. --}}
    @if (request()->is('admin*') && auth()->user()->hasRole('admin'))
        <nav class="container-alt grid grid-cols-3 pt-1">
            <div class="hidden sm:flex col-span-2">
                <x-nav-item_local-menu route-name="dashboard" icon="icon-dashboard">
                    <span class="hidden md:inline">{{ __('Dashboard') }}</span>
                </x-nav-item_local-menu>
                <x-nav-item_local-menu route-name="dboard.allurl" icon="icon-link">
                    <span class="hidden md:inline">{{ __('URL List') }}</span>
                </x-nav-item_local-menu>

                <x-nav-item_local-menu route-name="user.index" icon="icon-people">
                    <span class="hidden md:inline">{{ __('User List') }}</span>
                </x-nav-item_local-menu>

                <x-nav-item_local-menu route-name="dboard.settings" icon="icon-setting">
                    <span class="hidden md:inline">{{ __('Settings') }}</span>
                </x-nav-item_local-menu>

                <x-nav-item_local-menu route-name="dboard.about" icon="icon-about-system">
                    <span class="hidden md:inline">{{ __('About') }}</span>
                </x-nav-item_local-menu>
            </div>
            <x-nav-dropdown x-show="atTop" class="flex justify-end"/>
        </nav>
    @endif
</header>
