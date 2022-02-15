@include('partials/nav')

<header class="bg-white shadow">
  <div class="hidden sm:flex max-w-7xl mx-auto p-4 sm:px-6 lg:px-8 croll-smooth hover:scroll-auto">
    <a href="{{route('dashboard')}}" class="font-light text-uh-1 hover:text-indigo-900 active:text-gray-800 leading-tight mr-4">
      <i class="fas fa-tachometer-alt text-[#5e72e4]"></i>
      @lang('Dashboard')
    </a>
    @role('admin')
      <a href="{{route('dashboard.allurl')}}" class="font-light text-uh-1 hover:text-indigo-900 active:text-gray-800 leading-tight mr-4">
        <i class="nav-icon fas fa-link text-[#5e72e4]"></i>
        @lang('All URLs')
      </a>
      <a href="{{route('user.index')}}" class="font-light text-uh-1 hover:text-indigo-900 active:text-gray-800 leading-tight">
        <i class="nav-icon fas fa-users text-[#5e72e4]"></i>
        @lang('All Users')
      </a>
    @endrole
  </div>
</header>
