<header class="navbar shadow" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <a class="navbar-brand" href="{{ url('/') }}">{{appName()}}</a>
      @auth
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          {{-- Settings Dropdown --}}
          <div class="ml-3 relative">
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
              <div @click="open = ! open">
                <span class="inline-flex rounded-md">
                  <button type="button" class="navbar-toggler inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 rounded-md focus:outline-none transition">
                    <div class="text-base font-semibold">{{Str::title(Auth::user()->name)}}</div>

                    <svg class="navbar-toggler-icon ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                  </button>
                </span>
              </div>
              <div
                @click="open = false"
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0" style="display: none;"
              >
                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">

                  @if (Route::currentRouteName() != 'dashboard')
                    <a class="nav-item" href="{{route('dashboard')}}">@lang('Dashboard')</a>

                    <div class="border-t border-slate-100"></div>
                  @endif

                  <!-- Account Management -->
                  <div class="block px-4 py-2 text-xs text-slate-400">
                    @lang('Manage Account')
                  </div>

                  <a href="{{route('user.edit', Auth::user()->name)}}"
                    class="nav-item {{(request()->route()->getName() == 'user.edit') ? 'border-l-2 border-uh-indigo-400':''}}">
                    @lang('Profile')</a>
                  <a href="{{route('user.change-password', Auth::user()->name)}}"
                    class="nav-item {{(request()->route()->getName() == 'user.change-password') ? 'border-l-2 border-uh-indigo-400':''}}">
                    @lang('Change Password')</a>

                  <div class="border-t border-slate-100"></div>

                  <!-- Authentication -->
                  <form method="POST" action="{{route('logout')}}">
                  @csrf
                    <a href="{{route('logout')}}" onclick="event.preventDefault();
                      this.closest('form').submit();" class="nav-item" >@lang('Log Out')</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      @else
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <a href="{{route('login')}}" class="text-xl font-light leading-tight mr-4">@lang('Login')</a>
          @if (Route::has('register') and Config::get('urlhub.registration'))
            <a href="{{route('register')}}" class="text-xl font-light leading-tight mr-4">@lang('Register')</a>
          @endif
        </div>
      @endauth
        {{-- Hamburger --}}
        <div class="-mr-2 flex items-center sm:hidden">
          <button @click="open = ! open"
            class="inline-flex items-center justify-center p-2 rounded-md
              text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition"
          >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
    </div>
  </div>
  {{-- Responsive Navigation Menu --}}
  <div :class="{'block': open, 'hidden': ! open}" x-show="open" x-transition class="navbar-mobile sm:hidden block">
    @auth
      <div class="pt-2 pb-3 space-y-1">
        @if (Route::currentRouteName() != 'dashboard')
          <a href="{{route('dashboard')}}"
            class="nav-item {{(request()->route()->getName() == 'dashboard') ? 'border-l-2 border-uh-indigo-400':''}}">
            <i class="fas fa-tachometer-alt"></i> @lang('Dashboard')</a>
        @endif
        <a href="{{route('dashboard.allurl')}}"
          class="nav-item {{(request()->route()->getName() == 'dashboard.allurl') ? 'border-l-2 border-uh-indigo-400':''}}">
          <i class="nav-icon fas fa-link"></i> @lang('All URLs')</a>
        <a href="{{route('user.index')}}"
          class="nav-item {{(request()->route()->getName() == 'user.index') ? 'border-l-2 border-uh-indigo-400':''}}">
          <i class="nav-icon fas fa-users"></i> @lang('All Users')</a>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-slate-200">
        <div class="flex items-center px-4">
          <div>
            <div class="nav-item-username">{{Auth::user()->name}}</div>
            <div class="nav-item-email">{{Auth::user()->email}}</div>
          </div>
        </div>

        <div class="mt-3 space-y-1">
          <!-- Account Management -->
          <a href="{{route('user.edit', Auth::user()->name)}}"
            class="nav-item {{(request()->route()->getName() == 'user.edit') ? 'border-l-2 border-uh-indigo-400':''}}">
            @lang('Profile')</a>
          <a href="{{route('user.change-password', Auth::user()->name)}}"
            class="nav-item {{(request()->route()->getName() == 'user.change-password') ? 'border-l-2 border-uh-indigo-400':''}}">
            @lang('Change Password')</a>

          <!-- Authentication -->
          <form method="POST" action="{{route('logout')}}">
          @csrf
            <a class="nav-item" href="{{route('logout')}}" onclick="event.preventDefault();
              this.closest('form').submit();">
              @lang('Log Out')</a>
          </form>
        </div>
      </div>
    @else
      <div class="pt-2 pb-3 space-y-1">
        <a class="block pl-3 pr-4 py-2 font-medium transition" href="{{route('login')}}">
          @lang('Login')
        </a>
        @if (Route::has('register') and Config::get('urlhub.registration'))
          <a class="block pl-3 pr-4 py-2 font-medium transition" href="{{route('register')}}">
            @lang('Register')
          </a>
        @endif
      </div>
    @endauth
  </div> {{-- End Responsive Navigation Menu --}}

  {{-- It should only appear on the dashboard page only. --}}
  @if (request()->is('admin*'))
    <nav class="bg-white border-t border-slate-900/10 pt-1">
      <div class="hidden sm:flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 croll-smooth hover:scroll-auto">
        <a href="{{route('dashboard')}}"
          class="{{(request()->route()->getName() == 'dashboard') ?
            'text-slate-800 border-b-2 border-uh-indigo-400' :
            'text-slate-500 hover:border-b-2 hover:border-slate-300'}}
            font-semibold hover:text-slate-700 transition duration-150 ease-in-out leading-tight mr-8 py-3">
          <i class="fas fa-tachometer-alt mr-1"></i>
          <span class="">@lang('Dashboard')</span></a>
        @role('admin')
          <a href="{{route('dashboard.allurl')}}"
            class="{{(request()->route()->getName() == 'dashboard.allurl') ?
              'text-slate-800 border-b-2 border-uh-indigo-400' :
              'text-slate-500 hover:border-b-2 hover:border-slate-300'}}
              font-semibold hover:text-slate-700 transition duration-150 ease-in-out leading-tight mr-8 py-3">
            <i class="nav-icon fas fa-link mr-1"></i>
            <span class="">@lang('All URLs')</span></a>
          <a href="{{route('user.index')}}"
            class="{{(request()->route()->getName() == 'user.index') ?
              'text-slate-800 border-b-2 border-uh-indigo-400' :
              'text-slate-500 hover:border-b-2 hover:border-slate-300'}}
              font-semibold hover:text-slate-700 transition duration-150 ease-in-out leading-tight mr-8 py-3">
            <i class="nav-icon fas fa-users mr-1"></i>
            <span class="">@lang('All Users')</span>
</a>
        @endrole
      </div>
    </nav>
  @endif
</header>
