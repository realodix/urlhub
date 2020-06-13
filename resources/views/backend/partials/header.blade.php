<header class="app-header navbar">
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-brand">
    <div class="navbar-brand-full">{{app_name()}}</div>
    <div class="navbar-brand-minimized">{{app_name()}}</div>
  </div>

  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
    <span class="navbar-toggler-icon"></span>
  </button>

  <ul class="nav navbar-nav d-md-down-none">
    <li class="nav-item px-3">
        <a class="nav-link" id="homepage-icon" href="{{ url('./') }}" title="{{app_name()}} @lang('Home Page')" data-toggle="tooltip"><i class="fas fa-home"></i></a>
    </li>

    @if(Breadcrumbs::exists())
      {!! Breadcrumbs::render() !!}
    @endif
  </ul>

  <ul class="nav navbar-nav ml-auto mr-5">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <img class="img-avatar" src="{{ Auth::user()->avatar }}" alt="Avatar">
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <span class="dropdown-item" href="{{ route('dashboard') }}">
          @lang('Signed in as') {{ Str::title(Auth::user()->name) }}
        </span>
        <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->name) }}">
          @lang('Your Profile')
        </a>
        <a class="dropdown-item" href="{{ route('user.change-password', Auth::user()->name) }}">
          @lang('Change Password')
        </a>
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
           @lang('Sign out')
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        </form>
      </div>
    </li>
  </ul>
</header>
