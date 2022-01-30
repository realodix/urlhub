<nav class="bg-white border-b border-gray-100" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <a href="{{ url('/') }}" class="flex shrink-0 items-center text-nord10 text-3xl font-bold">{{appName()}}</a>
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        {{-- Settings Dropdown --}}
        <div class="ml-3 relative">
          <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <div @click="open = ! open">
              <span class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                  {{ Str::title(Auth::user()->name) }}

                  <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
                </button>
              </span>
            </div>
            <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0" @click="open = false" style="display: none;">
              <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  @lang('Manage Account')
                </div>

                <a href="{{route('user.edit', Auth::user()->name)}}"
                  class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition" >
                  @lang('Profile')</a>
                <a href="{{route('user.change-password', Auth::user()->name)}}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                  @lang('Change Password')</a>

                <div class="border-t border-gray-100"></div>

                <!-- Authentication -->
                <form method="POST" action="{{route('logout')}}">
                @csrf
                  <a href="{{route('logout')}}" onclick="event.preventDefault();
                  this.closest('form').submit();"
                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition" >
                    @lang('Log Out')</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- Hamburger --}}
      <div class="-mr-2 flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
  {{-- Responsive Navigation Menu --}}
  <div :class="{'block': open, 'hidden': ! open}" x-show="open" x-transition class="sm:hidden block">
    <div class="pt-2 pb-3 space-y-1">
      <a href="{{route('dashboard')}}" class="block pl-3 pr-4 py-2 text-base font-medium text-nord1 transition">
        <i class="fas fa-tachometer-alt"></i> @lang('Dashboard')
      </a>
      <a href="{{route('dashboard.allurl')}}" class="block pl-3 pr-4 py-2 text-base font-medium text-nord1 transition">
        <i class="nav-icon fas fa-link"></i> @lang('All URLs')
      </a>
      <a href="{{route('user.index')}}" class="block pl-3 pr-4 py-2 text-base font-medium text-nord1 transition">
        <i class="nav-icon fas fa-users"></i> @lang('All Users')
      </a>
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      <div class="flex items-center px-4">
        <div>
          <div class="font-medium text-base text-gray-800">{{Auth::user()->name}}</div>
          <div class="font-medium text-sm text-gray-500">{{Auth::user()->email}}</div>
        </div>
      </div>

      <div class="mt-3 space-y-1">
        <!-- Account Management -->
        <a href="{{route('user.edit', Auth::user()->name)}}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition">
          @lang('Profile')
        </a>
        <a href="{{route('user.change-password', Auth::user()->name)}}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition">
          @lang('Change Password')
        </a>

        <!-- Authentication -->
        <form method="POST" action="{{route('logout')}}">
        @csrf
          <a href="{{route('logout')}}" onclick="event.preventDefault();
          this.closest('form').submit();" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition">
            @lang('Log Out')
          </a>
        </form>
      </div>
    </div>
  </div> {{-- End Responsive Navigation Menu --}}
</nav>

<header class="bg-white shadow">
  <div class="hidden sm:flex max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 croll-smooth hover:scroll-auto">
    <a href="{{route('dashboard')}}" class="text-xl font-light text-nord0 hover:text-nord10 leading-tight mr-4">
      <i class="fas fa-tachometer-alt"></i>
      @lang('Dashboard')
    </a>
    @role('admin')
      <a href="{{route('dashboard.allurl')}}" class="text-xl font-light text-nord0 hover:text-nord10 leading-tight mr-4">
        <i class="nav-icon fas fa-link"></i>
        @lang('All URLs')
      </a>
      <a href="{{route('user.index')}}" class="text-xl font-light text-nord0 hover:text-nord10 leading-tight">
        <i class="nav-icon fas fa-users"></i>
        @lang('All Users')
      </a>
    @endrole
  </div>
</header>
