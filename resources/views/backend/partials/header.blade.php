@include('sections/nav')

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
