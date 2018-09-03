<header class="app-header navbar">
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
    <span class="navbar-toggler-icon"></span>
  </button>

  <a class="navbar-brand" href="{{ url('./') }}">
    <div class="navbar-brand-full">{{config('app.name')}}</div>
    <div class="navbar-brand-minimized">{{config('app.name')}}</div>
  </a>

  <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
    <span class="navbar-toggler-icon"></span>
  </button>

  <ul class="nav navbar-nav d-md-down-none">
    <li class="nav-item px-3">
        <a class="nav-link" href="{{ url('./') }}" target="_blank"><i class="icon-home"></i></a>
    </li>

    <li class="nav-item px-3">
        <a class="nav-link" href="{{ route('admin') }}">Dashboard</a>
    </li>
  </ul>

  <ul class="nav navbar-nav ml-auto mr-5">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        {{ title_case(Auth::user()->name) }}
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="#">
          <i class="fas fa-user"></i> Edit Profile
        </a>
        <a class="dropdown-item" href="#">
          <i class="fas fa-key"></i> Change Password
        </a>
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i>
          {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        </form>
      </div>
    </li>
  </ul>
</header>
