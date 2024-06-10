<header class="navbar" x-data="{ open: false }">
    <div class="layout-container flex
        px-4 sm:px-6 lg:px-8 h-16 justify-between"
    >
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>

        @auth
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                {{-- Settings Dropdown --}}
                <div class="ml-3 relative">
                    <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                        <div x-on:click="open = ! open">
                            <span class="inline-flex rounded-md">
                                <button class="navbar-toggler items-center">
                                    <div class="text-base font-semibold">{{ str()->title(auth()->user()->name) }}</div>

                                    <svg class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
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

                                    <div class="border-t border-uh-border-color"></div>
                                @endif

                                {{-- Account Management --}}
                                <div class="block px-4 py-2 text-xs text-slate-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <a href="{{ route('user.edit', auth()->user()->name) }}"
                                    class="nav-item {{ (request()->route()->getName() === 'user.edit') ? 'border-l-2 border-orange-500':'' }}">
                                    @svg('icon-person', 'mr-1') {{ __('Account') }}</a>
                                <a href="{{ route('user.password.show', auth()->user()->name) }}"
                                    class="nav-item {{ (request()->route()->getName() === 'user.password.show') ? 'border-l-2 border-orange-500':'' }}">
                                    @svg('icon-key', 'mr-1') {{ __('Change Password') }}</a>

                                <div class="border-t border-uh-border-color"></div>

                                {{-- Authentication --}}
                                <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="nav-item"
                                    >
                                        @svg('icon-log-out', 'mr-1') {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <a href="{{ route('login') }}"
                    class="text-xl font-medium text-gray-500 hover:text-gray-900 mr-8">{{ __('Log in') }}</a>
                @if (Route::has('register') and Config::get('urlhub.registration'))
                    <a href="{{ route('register') }}"
                        class="text-xl font-medium text-white bg-uh-indigo-600 hover:bg-uh-indigo-700 active:bg-uh-indigo-600
                        px-4 py-2 rounded-md"
                    >
                        {{ __('Sign up') }}
                    </a>
                @endif
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

    {{-- Responsive Navigation Menu --}}
    <div class="navbar-mobile sm:hidden block"
        :class="{'block': open, 'hidden': ! open}" x-show="open"
        x-transition
        {{-- Prevent blinking --}}
        style="display: none;"
    >
        @auth
            @include('partials.header-localmenu_mobile')

            {{-- Responsive Settings Options --}}
            <div class="pt-4 pb-1 border-t border-uh-border-color">
                <div class="flex items-center px-4">
                    <div>
                        <div class="nav-item-username">{{ auth()->user()->name }}</div>
                        <div class="nav-item-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    {{-- Account Management --}}
                    <a href="{{ route('user.edit', auth()->user()->name) }}"
                        class="nav-item {{ (request()->route()->getName() === 'user.edit') ? 'border-l-2 border-orange-500':'' }}">
                        @svg('icon-person', 'mr-1') {{ __('Account') }}</a>
                    <a href="{{ route('user.password.show', auth()->user()->name) }}"
                        class="nav-item {{ (request()->route()->getName() === 'user.password.show') ? 'border-l-2 border-orange-500':'' }}">
                        @svg('icon-key', 'mr-1') {{ __('Change Password') }}</a>

                    {{-- Authentication --}}
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <a class="nav-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            @svg('icon-log-out', 'mr-1') {{ __('Log Out') }}
                        </a>
                    </form>
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
        @include('partials.header-localmenu')
    @endif
</header>
