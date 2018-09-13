<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{config('app.name')}}</title>

  {!! style(mix('css/frontend.css')) !!}
</head>

<body class="@yield('css_class')">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">{{config('app.name')}}</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto"></ul>
      <ul class="navbar-nav">
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ title_case(Auth::user()->name) }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('admin') }}">
                <i class="dropdown-icon fas fa-tachometer-alt"></i> @lang('Dashboard')
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->name) }}">
                <i class="dropdown-icon fas fa-user"></i> @lang('Your Profile')
              </a>
              <a class="dropdown-item" href="{{ route('user.change-password', Auth::user()->name) }}">
                <i class="dropdown-icon fas fa-key"></i> @lang('Change Password')
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                <i class="dropdown-icon fas fa-sign-out-alt"></i>
                {{ __('Logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
              </form>
            </div>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

@yield('content')

{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/frontend.js')) !!}
@if(config('app.env') == 'local')
  <script src="http://localhost:35729/livereload.js"></script>
@endif
</body>
</html>
